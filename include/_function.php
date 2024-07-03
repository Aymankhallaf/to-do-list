<?php

/**
 * Get HTML to display errors available in user SESSION
 *
 * @param array $errorsList - Available errors list
 * @return string HTMl to display errors
 */
function getHtmlErrors(array $errorsList): string
{
    if (!empty($_SESSION['errorsList'])) {
        $errors = $_SESSION['errorsList'];
        unset($_SESSION['errorsList']);
        return '<ul class="notif-error">'
            . implode(array_map(fn ($e) => '<li>' . $errorsList[$e] . '</li>', $errors))
            . '</ul>';
    }
    return '';
}

/**
 * Get HTML to display messages available in user SESSION
 *
 * @param array $messagesList - Available Messages list
 * @return string HTML to display messages
 */
function getHtmlMessages(array $messagesList): string
{
    if (isset($_SESSION['msg'])) {
        $m = $_SESSION['msg'];
        unset($_SESSION['msg']);
        return '<p class="notif-success">' . $messagesList[$m] . '</p>';
    }
    return '';
}

/**
 * Add a new error message to display on next page. 
 *
 * @param string $errorMsg - Error message to display
 * @return void
 */
function addError(string $errorMsg): void
{
    if (!isset($_SESSION['errorsList'])) {
        $_SESSION['errorsList'] = [];
    }
    $_SESSION['errorsList'][] = $errorMsg;
}



/**
 * redirect to url and 
 *
 * @param string $url the target url
 * @param string $flag a flag to differentiate the error.
 * @return void
 */
function redirectToHeader(string $url, string $flag = ''): void
{
    // var_dump('REDIRECT ' . $url, $flag);
    header('Location: ' . $url);
    exit;
}


/**
 * verify HTTP_REFERER
 *
 * @return void
 */
function verifyServer(string $redirectUrl = 'index.php'): void
{
    global $globalUrl;

    if (!isset($_SERVER['HTTP_REFERER']) || !str_contains($_SERVER['HTTP_REFERER'], $globalUrl)) {
        addError('referer');
        redirectToHeader($redirectUrl);
    }
}

/**
 * verify session token
 *
 * @return void
 */
function verifyToken(string $redirectUrl = 'index.php'): void
{
    if (!isset($_SESSION['myToken']) || !isset($_REQUEST['myToken']) || $_SESSION['myToken'] !== $_REQUEST['myToken']) {
        addError('csrf');
        redirectToHeader($redirectUrl);
    }
}


/**
 *  verify the length of the task
 *
 * @param integer $maxNumber the maximum lenght of characters.
 * @return void
 */
function verifyNbChars(int $maxNumber): void
{
    if (isset($_REQUEST['task_title'])) {

        if (strlen($_REQUEST['task_title']) > $maxNumber || strlen($_REQUEST['task_title']) < 0) {
            addError('nb_char_ko');
            redirectToHeader('index.php');
        }
    }
}


/**
 * get non terminated tasks order by creation date order.
 *
 * @param [type] $dbCo the object dbco who mange the database connection
 * @return object  of tasks
 */
function getNonTerminatedTask($dbCo)
{
    $query = $dbCo->prepare("SELECT id_task, title_task FROM task WHERE is_terminate = 0  ORDER BY creation_date DESC;");
    $query->execute();
    return $query;
}


/**
 * get priority tasks asc order.
 *
 * @param [type] $dbCo the object dbco who mange the database connection
 * @return object  of tasks
 */
function getPriorityTasks($dbCo)
{
    $query = $dbCo->prepare("SELECT id_task, title_task FROM task WHERE is_terminate = 0 AND rank_task IS NOT NULL  ORDER BY rank_task ASC;");
    $query->execute();
    return $query;
}

/**
 * 
 * show all the tasks in an array
 *
 * @param array $lsttasks a list of tasks
 * @return string string of html tages + task
 */
function showLsTasks(array $lsttasks)
{

    $li = '';
    foreach ($lsttasks as $task) {
        $li .= '<li data-id=' . $task['id_task'] . ' id=' . $task['id_task'] . ' class="border-container task-lst-item js-drage" draggable="true">
        <label class="hide task-lst-item-done" for="done" draggable="false"> done </label>
        <input role="checkbox if the task has been done" class="task-lst-item-checkbox" type="checkbox" id="done" name="done" value="1" draggable="false">
        <p class="js-task-title_txt" draggable="false">' . $task['title_task'] . '</p>
        <a href="action.php?action=archive&id_task=' . $task['id_task'] . '&myToken=' . $_SESSION['myToken'] . '" draggable="false">
        <img aria-hidden="true" src="/img/archive.svg" alt="archive task" draggable="false">
        </a>
        <button id=' . $task['id_task'] . ' class="task-edit js-edit-task-title" type="submit" role="edit-task" draggable="false"><img aria-hidden="true" src="/img/edit.svg" alt="edit task" draggable="false"></button>
         <a href="action.php?action=up_rank&id_task=' . $task['id_task'] . '&myToken=' . $_SESSION['myToken'] . '" draggable="false">
        <img aria-hidden="true" src="/img/up_rank.svg" alt="periorty task" draggable="false">
        </a>
        <a href="action.php?action=down_rank&id_task=' . $task['id_task'] . '&myToken=' . $_SESSION['myToken'] . '" draggable="false">
        <img aria-hidden="true" src="/img/down_rank.svg" alt="periorty task" draggable="false">
        </a>
        <button class="task-delete" type="submit" role="delete-task" draggable="false"><img aria-hidden="true" src="/img/delete.svg" alt="delete task" draggable="false"></a>
      </li>';
    }
    return $li;
};


/**
 * add task to data base
 *
 * @param [type] $dbCo connection
 * @return void
 */
function addTask($dbCo)
{

    verifyNbChars(255);
    $insertTask = $dbCo->prepare("INSERT INTO task 
        (title_task, creation_date) VALUES 
        (:task_title, CURRENT_DATE())");


    $bindValue = ([
        ':task_title' => htmlspecialchars($_REQUEST['task_title'])
    ]);

    $isInsertOk = $insertTask->execute($bindValue);

    if ($isInsertOk) {
        $_SESSION['msg'] = 'insert_ok';
    } else {
        $_SESSION['errors'] = 'insert_ko';
        addError('insert_ko');
    }
    redirectToHeader('index.php');
}


/**
 * Archieves task "set terminted task to true(it won't be shown in home page)"
 *
 * @param [type] $dbCo connection
 * @return void
 */
function archiveTask($dbCo)
{

    if (isset($_REQUEST['id_task']) && is_numeric($_REQUEST['id_task'])) {

        $query = $dbCo->prepare("UPDATE task SET is_terminate = '1' WHERE id_task = :task_id;");

        $isInsertOk = $query->execute(['task_id' => intval($_REQUEST['id_task'])]);

        if ($isInsertOk) {
            $_SESSION['msg'] = 'archive_ok';
        } else {
            $_SESSION['errors'] = 'archive_ko';
        }

        redirectToHeader("index.php");
    }
}


/**
 * edit task title and save it in database.
 *  
 * @param [type] $dbCo connection
 * @return void
 */
function editTasktitle($dbCo)
{
    verifyNbChars(255);
    if (isset($_REQUEST['task_id']) && is_numeric($_REQUEST['task_id'])) {
        $query = $dbCo->prepare("UPDATE task SET up_rank = 1 WHERE id_task = :task_id;");

        $isInsertOk = $query->execute([
            ':task_id' => intval($_REQUEST['task_id']),
        ]);

        if ($isInsertOk) {
            $_SESSION['msg'] = 'insert_ok';
        } else {
            $_SESSION['errors'] = 'insert_ko';
        }
        redirectToHeader('index.php');
    }
}


/**
 * up task rank and save it in database.
 *  
 * @param [type] $dbCo connection
 * @return void
 */
function upTaskRank($dbCo)
{
    if (isset($_REQUEST['id_task']) && is_numeric($_REQUEST['id_task'])) {

        $query = $dbCo->prepare("UPDATE task SET rank_task = rank_task - 1 WHERE id_task = :task_id;");

        $isInsertOk = $query->execute(['task_id' => intval($_REQUEST['id_task'])]);

        if ($isInsertOk) {
            $_SESSION['msg'] = 'archive_ok';
        } else {
            $_SESSION['errors'] = 'archive_ko';
        }

        redirectToHeader("index.php");
    }
}


/**
 * down task rank and save it in database.
 *  
 * @param [type] $dbCo connection
 * @return void
 */
function downTaskRank($dbCo)
{
    if (isset($_REQUEST['id_task']) && is_numeric($_REQUEST['id_task'])) {

        $query = $dbCo->prepare("UPDATE task SET rank_task = rank_task + 1 WHERE id_task = :task_id;");

        $isInsertOk = $query->execute(['task_id' => intval($_REQUEST['id_task'])]);

        if ($isInsertOk) {
            $_SESSION['msg'] = 'archive_ok';
        } else {
            $_SESSION['errors'] = 'archive_ko';
        }

        redirectToHeader("index.php");
    }
}
