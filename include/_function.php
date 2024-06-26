<?php


// $errors = [
//     'csrf' => 'Votre session est invalide.',
//     'referer' => 'D\'où venez vous ?',
//    
// ];
// $messages = [
//     'archive_ok' => 'The task has been archieved',
//     'archive_ko' => 'Archieved faild'
// ];

function showMsg()
{
}

/**
 * show tasks by creation date order without Showing terminated tasks.
 *
 * @param [type] $dbCo the object dbco who mange the database connection
 * @return object  of tasks
 */
function getDataFromDAtabase($dbCo)
{
    $query = $dbCo->prepare("SELECT id_task, title_task FROM task WHERE is_terminate = 0 ORDER BY creation_date DESC;");
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
        $li .= '<li class="border-container task-lst-item">
        <label class="hide task-lst-item-done" for="done"> done </label>
        <input role="checkbox if the task has been done" class="task-lst-item-checkbox" type="checkbox" id="done" name="done" value="1">
        <p>' . $task['title_task'] . '</p><a href="?action=archive&id_task=' . $task['id_task'] . '" ><img aria-hidden="true" src="/img/archive.svg" alt="archive task"></a><button class="task-edit" type="submit" role="edit-task"><img aria-hidden="true" src="/img/edit.svg" alt="edit task"></button>
        <button class="task-delete" type="submit" role="delete-task"><img aria-hidden="true" src="/img/delete.svg" alt="delete task"></button>
      </li>';
    }
    return $li;
};



function addTask(string $postTaskTitle, $dbCo)
{
    //verify server
    if (isset($_SERVER) && str_contains($_SERVER['HTTP_REFERER'], 'http://localhost:8080')) {
        var_dump("comes from our server connection");

        if (isset(($_SESSION['myToken'])) && isset($_POST['myToken']) && $_SESSION['myToken'] === $_POST['myToken']) {

            //verify the length of the task
            if (
                isset($postTaskTitle) && strlen($postTaskTitle) > 0
            ) {

                $insertTask = $dbCo->prepare("INSERT INTO task 
        (title_task, creation_date) VALUES 
        (:task_title, CURRENT_DATE())");


                $bindValue = ([
                    ':task_title' => htmlspecialchars($postTaskTitle)
                ]);

                $isnsertOK = $insertTask->execute($bindValue);
                $nb = $insertTask->rowCount();

                if (strlen($postTaskTitle) > 255) {
                    var_dump("it is too long, plz write a shorter task");
                }
            }
        }
    }
}


/**
 * verify HTTP_REFERER
 *
 * @return void
 */
function verifyServer()
{
    if (!isset($_SERVER['HTTP_REFERER']) || !str_contains($_SERVER['HTTP_REFERER'], 'http://localhost:8080')) {
        $_SESSION['error'] = 'referer';
        redirectToHeader("index.php,1");
    }
}

/**
 * verify session token
 *
 * @return void
 */
function verifyToken():void
{
    if (!isset($_SESSION['myToken']) || !isset($_REQUEST['myToken']) || $_SESSION['myToken'] !== $_REQUEST['myToken']) {
        $_SESSION['error'] = 'csrf';
        redirectToHeader("index.php", 2);
    }
}



function archiveTasks($dbCo)
{

    if (!empty($_GET) && isset($_GET['action']) && $_GET['action'] === 'archive' && isset($_GET['id_task']) && is_numeric($_GET['id_task'])) {

        $query = $dbCo->prepare("UPDATE task SET is_terminate = '1' WHERE id_task = :task_id;");

        $isInsertOk = $query->execute(['task_id' => intval($_GET['id_task'])]);
        if ($isInsertOk) {
            $_SESSION['msg'] = 'archive_ok';
        } else {
            $_SESSION['error'] = 'archive_ko';
        }
        redirectToHeader("index.php", 3);
    }
}


function redirectToHeader(string $url, string $flag = ''): void
{
    var_dump('REDIRECT ' . $url, $flag);
    //  header('Location: ' . $url);
    exit;
}
