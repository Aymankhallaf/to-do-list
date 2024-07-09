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
 * Check for CSRF token
 *
 * @return boolean Is there a valid toekn in user session ?
 */
function isTokenOk(): bool
{
    return (!isset($_SESSION['myToken']) || !isset($_REQUEST['myToken']) || $_SESSION['myToken'] !== $_REQUEST['myToken']);
}

/**
 * Check fo referer
 *
 * @return boolean Is the current referer valid ?
 */
function isServerOk(): bool
{
    global $globalUrl;
    return isset($_SERVER['HTTP_REFERER'])
        && str_contains($_SERVER['HTTP_REFERER'], $globalUrl);
}




 /**
  * verify the length of the task
  *
  * @param integer $maxNumber the maximum lenght of characters.
  * @param string $taskTitle the task title
  * @return void
  */
function verifyNbChars(int $maxNumber, string $taskTitle): void
{
    if (isset($taskTitle)) {

        if (strlen($taskTitle) > $maxNumber || strlen($taskTitle) < 0) {
            addError('nb_char_ko');
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
    $query = $dbCo->prepare("SELECT id_task, title_task, DATE_FORMAT(planning_date, '%d/%m/%Y') AS planning_date FROM task WHERE is_terminate = 0 AND rank_task is NULL ORDER BY creation_date DESC;");
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
    $query = $dbCo->prepare("SELECT id_task, title_task, DATE_FORMAT(planning_date, '%d/%m/%Y') AS planning_date FROM task WHERE is_terminate = 0 AND rank_task IS NOT NULL  ORDER BY rank_task ASC;");
    $query->execute();
    return $query->fetchAll();
}


/**
 * get  terminated tasks .
 *
 * @param PDO $dbCo the class PDO who mange the database connection
 * @return array  of tasks
 */
function getTerminatedTask(PDO $dbCo): array
{
    $query = $dbCo->prepare("SELECT id_task, title_task, DATE_FORMAT(planning_date, '%d/%m/%Y') AS planning_date FROM task WHERE is_terminate = 1;");
    $query->execute();
    return $query->fetchAll();
}

/**
 * 
 * add html tags"li + p + a (delete, archive,..  ) all the tasks in an array
 *
 * @param array $lsttasks a list of tasks
 * @return string string of html tages + task
 */
function addHtmlTags(array $lsttasks): string
{

    $li = '';
    foreach ($lsttasks as $task) {
        $deleteTag = addDeletehtml($task);
        $archiveTag = addArchivehtml($task, "unarchive");
        $li .= '<li data-id="' . $task['id_task'] . '" class="border-container task-lst-item js-drage" draggable="true">
        <label class="hide task-lst-item-done" for="done" draggable="false">done</label>
        <input role="checkbox" class="task-lst-item-checkbox" type="checkbox" name="done" value="1" draggable="false">
        <p class="js-task-title_txt" draggable="false">' . $task['title_task'] . '</p>
        <time value="' . $task['planning_date'] . '" class="js-planning-date" datetime="' . $task['planning_date'] . '">' . $task['planning_date'] . '</time>
       ' . $archiveTag . $deleteTag . '</li>';
    }
    return $li;
};

/**
 * add delete html.
 *
 * @param array $task a task.
 * @return string a html task.
 */
function addDeletehtml(array $task): string
{

    return '<a href="action.php?action=delete&id_task=' . $task['id_task'] . '&myToken=' . $_SESSION['myToken'] . '" draggable="false">
    <img aria-hidden="true" src="/img/delete.svg" alt="delete task" draggable="false">
</a>';
}

/**
 * add archieve html.
 * @param string $task a name.
 * @param array $task a task.
 * @return string a html task.
 */
function addArchivehtml(array $task, string $iconName): string
{

    return '<button class="js-archive" data-archive-id=' . $task['id_task'] . ' draggable="false">
    <img aria-hidden="true" src="/img/' . $iconName . '.svg" alt="$iconName task" draggable="false">
</button>';
}

/**
 * add rank html.
 *
 * @param array $task a task.
 * @return string a html task.
 */
function addRankhtml(array $task): string
{

    return ' <a href="action.php?action=up_rank&id_task=' . $task['id_task'] . '&myToken=' . $_SESSION['myToken'] . '" draggable="false">
    <img aria-hidden="true" src="/img/up_rank.svg" alt="priority task" draggable="false">
</a>
<a href="action.php?action=down_rank&id_task=' . $task['id_task'] . '&myToken=' . $_SESSION['myToken'] . '" draggable="false">
    <img aria-hidden="true" src="/img/down_rank.svg" alt="priority task" draggable="false">
</a>';
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
        $rankTag = addRankhtml($task);
        $deleteTag = addDeletehtml($task);
        $archiveTag = addArchivehtml($task, "archive");
        $li .= '<li data-id="' . $task['id_task'] . '" class="border-container task-lst-item js-drage" draggable="true">
        <label class="hide task-lst-item-done" for="done" draggable="false">done</label>
        <input role="checkbox" class="task-lst-item-checkbox" type="checkbox" name="done" value="1" draggable="false">
        <p class="js-task-title_txt" draggable="false">' . $task['title_task'] . '</p>
        <time value="' . $task['planning_date'] . '" class="js-planning-date" datetime="' . $task['planning_date'] . '">' . $task['planning_date'] . '</time>
        ' . $archiveTag . $rankTag . $deleteTag . '</li>';
    }
    return $li;
};


/**
 * add task to database
 *
 * @param PDO $dbCo connection 
 * @return void
 */
function addTask(PDO $dbCo, array $task ): void
{

    verifyNbChars(255, $task['titleTask']);
    $insertTask = $dbCo->prepare("INSERT INTO task 
        (title_task, creation_date, planning_date) VALUES 
        (:task_title, CURRENT_DATE(),:planning_date);");


    $bindValue = ([
        ':task_title' => htmlspecialchars($task['titleTask']),
        ':planning_date' => $task['planningDate']
    ]);

    $isInsertOk = $insertTask->execute($bindValue);

    if ($isInsertOk) {
        echo json_encode([
            'isOk' => $isInsertOk
        ]);
        $_SESSION['msg'] = 'insert_ok';
    } else {
        $_SESSION['errors'] = 'insert_ko';
        addError('insert_ko');
    }
}


/**
 * Archive/unarchive task.
 * @param int $task_id the task id.
 * @param PDO $dbCo connection database.
 * @return void
 */
function archiveTask(PDO $dbCo, int $task_id): void
{


    $query = $dbCo->prepare("UPDATE task
    SET is_terminate = CASE 
    WHEN is_terminate = 0 THEN 1 
    ELSE 0 
    END
    WHERE id_task = :task_id;");

    $isArchive = $query->execute(['task_id' => $task_id]);

    $querySelect = $dbCo->prepare("SELECT is_terminate FROM task
    WHERE id_task = :task_id;");

    $querySelect->execute(['task_id' => $task_id]);
    $terminateValue = $querySelect->fetchColumn();

    if ($isArchive) {
        echo json_encode([
            'isOk' => $isArchive,
            'archive' => $terminateValue,
            'id' => intval($task_id)
        ]);
        $_SESSION['msg'] = 'archive_ok';
    } else {
        $_SESSION['errors'] = 'archive_ko';
    }
}



/**
 * edit task title and save it in database.
 * @param array $task the task array
 * @param PDO $dbCo connection database
 * @return void
 */
function editTasktitle(PDO $dbCo, array $task): void
{
    // verifyNbChars(255, );
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
        $isUpdateOk = updateAllRanks($dbCo, $targetId);

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
function updateAllRanks(PDO $dbCo, int $targetId): bool
{

    $query = $dbCo->prepare("UPDATE task SET rank_task = rank_task -1 WHERE id_task > :targetId");
    return $query->execute([
        'targetId' => $targetId
    ]);
}



/**
 * get planning today's tasks
 *
 * @param PDO $dbCo data base connection
 * @return array array of tasks
 */
function getTodayTask(PDO $dbCo): array
{

    $query = $dbCo->prepare("SELECT title_task FROM task WHERE (DATE(planning_date) = timestamp(CURRENT_DATE()))
    AND is_terminate = 0;");
    $query->execute();
    return $query->fetchAll();
}



/**
 * add html to lst of task.
 *
 * @param array $lsttasks lst of tasks
 * @return string html tag
 */
function showTasktitle(array $lsttasks): string
{
    $li = '';
    foreach ($lsttasks as $task) {
        $li .= '<li class="border-container task-lst-item js-drage" draggable="true">
        <p class="js-task-title_txt" draggable="false">' . $task['title_task'] . '</p>';
    }
    return $li;
}
