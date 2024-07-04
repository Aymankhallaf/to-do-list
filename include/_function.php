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
 * verify session token
 *
 * @return void
 */
function verifyIdTask(string $redirectUrl = 'index.php'): void
{
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
 * @param PDO $dbCo the class PDO who mange the database connection
 * @return array  of tasks
 */
function getNonTerminatedTask(PDO $dbCo): array
{
    $query = $dbCo->prepare("SELECT id_task, title_task, DATE_FORMAT(planning_date, '%d/%m/%Y') as planning_date FROM task WHERE is_terminate = 0 AND rank_task is NULL ORDER BY creation_date DESC;");
    $query->execute();
    return $query->fetchAll();
}


/**
 * get priority tasks asc order.
 *
 * @param PDO $dbCo the object dbco who mange the database connection
 * @return array  of tasks
 */
function getPriorityTasks(PDO $dbCo): array
{
    $query = $dbCo->prepare("SELECT id_task, title_task, DATE_FORMAT(planning_date, '%d/%m/%Y') as planning_date FROM task WHERE is_terminate = 0 AND rank_task IS NOT NULL  ORDER BY rank_task ASC;");
    $query->execute();
    return $query->fetchAll();
}

/**
 * 
 * show all the tasks in an array
 *
 * @param array $lsttasks a list of tasks
 * @return string string of html tages + task
 */
function showLsTasks(array $lsttasks): string
{

    $li = '';
    foreach ($lsttasks as $task) {
        $li .= '<li data-id="' . $task['id_task'] . '" id="' . $task['id_task'] . '" class="border-container task-lst-item js-drage" draggable="true">
        <label class="hide task-lst-item-done" for="done" draggable="false">done</label>
        <input role="checkbox" class="task-lst-item-checkbox" type="checkbox" id="done" name="done" value="1" draggable="false">
        <p class="js-task-title_txt" draggable="false">' . $task['title_task'] . '</p>
        <time value="' . $task['planning_date'] . '" class="js-planning-date" datetime="' . $task['planning_date'] . '">' . $task['planning_date'] . '</time>
        <a href="action.php?action=archive&id_task=' . $task['id_task'] . '&myToken=' . $_SESSION['myToken'] . '" draggable="false">
            <img aria-hidden="true" src="/img/archive.svg" alt="archive task" draggable="false">
        </a>
        <button id="' . $task['id_task'] . '" class="task-edit js-edit-task-title" type="submit" role="edit-task" draggable="false">
            <img aria-hidden="true" src="/img/edit.svg" alt="edit task" draggable="false">
        </button>
        <a href="action.php?action=up_rank&id_task=' . $task['id_task'] . '&myToken=' . $_SESSION['myToken'] . '" draggable="false">
            <img aria-hidden="true" src="/img/up_rank.svg" alt="priority task" draggable="false">
        </a>
        <a href="action.php?action=down_rank&id_task=' . $task['id_task'] . '&myToken=' . $_SESSION['myToken'] . '" draggable="false">
            <img aria-hidden="true" src="/img/down_rank.svg" alt="priority task" draggable="false">
        </a>
        <a href="action.php?action=delete&id_task=' . $task['id_task'] . '&myToken=' . $_SESSION['myToken'] . '" draggable="false">
            <img aria-hidden="true" src="/img/delete.svg" alt="delete task" draggable="false">
        </a>
      </li>';
    }
    return $li;
};


/**
 * add task to database
 *
 * @param PDO $dbCo connection 
 * @return void
 */
function addTask(PDO $dbCo): void
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
 * @param int $task_id the task id
 * @param PDO $dbCo connection database
 * @return void
 */
function archiveTask(PDO $dbCo, int $task_id): void
{

    $query = $dbCo->prepare("UPDATE task SET is_terminate = '1' WHERE id_task = :task_id;");

    $isInsertOk = $query->execute(['task_id' => $task_id]);

    if ($isInsertOk) {
        $_SESSION['msg'] = 'archive_ok';
    } else {
        $_SESSION['errors'] = 'archive_ko';
    }

    redirectToHeader("index.php");
}



/**
 * edit task title and save it in database.
 * @param array $task the task array
 * @param PDO $dbCo connection database
 * @return void
 */
function editTasktitle(PDO $dbCo, array $task): void
{
    verifyNbChars(255);
    $query = $dbCo->prepare("UPDATE task SET title_task = :task_title WHERE id_task = :task_id;
    UPDATE task SET Planning_date = :Planning_date WHERE id_task = :task_id;");

    $isInsertOk = $query->execute([
        ':task_id' => intval($task['task_id']),
        ':task_title' => htmlspecialchars($task['task_title']),
        ':Planning_date' => $task['Planning_date']
    ]);

    if ($isInsertOk) {
        $_SESSION['msg'] = 'insert_ok';
    } else {
        $_SESSION['errors'] = 'insert_ko';
    }
    redirectToHeader('index.php');
}



/**
 * Update rank by giving a value for a target id.
 *
 * @param PDO $dbCo database connection
 * @param integer $changingValue the given value 
 * @param integer $targetId the target id 
 * @return bool True for successful excution
 */
function updateRank(PDO $dbCo, int $changingValue, int $targetId): bool
{
    $query = $dbCo->prepare("UPDATE task SET rank_task = rank_task + :changingValue WHERE id_task = :targetId");
    return $query->execute([
        'targetId' => $targetId,
        'changingValue' => $changingValue
    ]);
}

/**
 * Get id_task by rank.
 *
 * @param PDO $dbCo database connection
 * @param integer $targetRank rank
 * @return int|null task id or null if there is no result
 */
function getIdByRank(PDO $dbCo, int $targetRank): ?int
{
    $query = $dbCo->prepare("SELECT id_task FROM task WHERE rank_task = :targetRank;");
    $query->execute([
        'targetRank' => $targetRank
    ]);
    return $query->fetchColumn() ?: null;
}

/**
 * Get rank_task by id_task.
 *
 * @param PDO $dbCo database connection
 * @param integer $targetId task id
 * @return int|null rank_task or null if there is no result
 */
function getRankById(PDO $dbCo, int $targetId): ?int
{
    $query = $dbCo->prepare("SELECT rank_task FROM task WHERE id_task = :targetId;");
    $query->execute([
        'targetId' => $targetId
    ]);
    return $query->fetchColumn() ?: null;
}


/**
 * swap rank (Up or down)task rank and save it in the database.
 *  
 * @param PDO $dbCo Connection to the database
 * @param int $changingValue -1 to increase rank, +1 to decrease rank
 * @param int $task_id ID of the task to change rank
 * @return void
 */
function swapRank(PDO $dbCo, int $changingValue, int $task_id): void
{
    try {
        $dbCo->beginTransaction();

        $currentRank = getRankById($dbCo, $task_id);
        if ($currentRank === null) {
            $_SESSION['errors'] = 'invalid_task_id';
            redirectToHeader("index.php");
            return;
        }

        $targetRank = $currentRank + $changingValue;
        $idToMove = getIdByRank($dbCo, $targetRank);

        if ($idToMove !== null) {
            updateRank($dbCo, -$changingValue, $idToMove);
        }

        $isUpdateOk = updateRank($dbCo, $changingValue, $task_id);

        if ($isUpdateOk) {
            $dbCo->commit();
            $_SESSION['msg'] = 'update_periorty_ok';
        } else {
            $dbCo->rollBack();
            $_SESSION['errors'] = 'update_periorty_KO';
        }
    } catch (Exception $e) {
        $dbCo->rollBack();
        $_SESSION['errors'] = 'update_priority_ko';
    }

    redirectToHeader("index.php");
}


/**
 * delete task.
 *  
 * @param PDO $dbCo connection database
 * @return void
 */
function deleteTask(PDO $dbCo, int $targetId): void
{
    try {
        $dbCo->beginTransaction();
        $query = $dbCo->prepare("DELETE FROM task WHERE id_task = :task_id;");

        $isDeleteOk = $query->execute(['task_id' => $targetId]);
        $isUpdateOk= updateAllRanks($dbCo,$targetId);

        if ($isDeleteOk && $isUpdateOk) {
            $dbCo->commit();
            $_SESSION['msg'] = 'delete_ok';
        } else {
            $_SESSION['errors'] = 'delete_ko';
        }
    } catch (Exception $e) {
        $dbCo->rollBack();
        $_SESSION['errors'] = 'update_priority_ko';
    }

    redirectToHeader("index.php");
}


/**
 * Update rank by giving a value for a target id.
 *
 * @param PDO $dbCo database connection
 * @param integer $targetId the target id 
 * @return bool True for successful excution
 */
function updateAllRanks(PDO $dbCo, int $targetId):bool
{

    $query = $dbCo->prepare("UPDATE task SET rank_task = rank_task -1 WHERE id_task > :targetId");
    return $query->execute([
        'targetId' => $targetId
    ]);
}



function getTodayTask(PDO $dbCo):array{

    $query= $dbCo->prepare("SELECT title_task 
    FROM task WHERE (DATE(planning_date) = timestamp(CURRENT_DATE()))
    AND is_terminate = 0;");
     $query->execute();
     return $query->fetchAll();

} 