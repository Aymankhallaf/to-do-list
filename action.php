<?php
session_start();
include 'include/_connection.php';
include 'include/_function.php';
include 'include/_config.php';


if (!isset($_REQUEST['action'])) {
    redirectToHeader('index.php');
}


verifyServer();
verifyToken();


if ($_REQUEST['action'] === 'insert') {

    //call the function to insert task to database
    addTask($dbCo);
} else if ($_REQUEST['action'] === 'archive') {
    if (isset($_REQUEST['id_task']) && is_numeric($_REQUEST['id_task'])) {

        archiveTask($dbCo, $_REQUEST['id_task']);
    }
} else if ($_REQUEST['action'] === 'edit') {
    verifyNbChars(255);
    if (isset($_REQUEST['task_id']) && is_numeric($_REQUEST['task_id'])) {

        editTasktitle($dbCo, $_REQUEST);
    }
} else if ($_REQUEST['action'] === 'up_rank') {
    if (isset($_REQUEST['id_task']) && is_numeric($_REQUEST['id_task'])) {

        updateRank($dbCo, -1, $_REQUEST['id_task']);
        if ($isUpdateOk) {
            $_SESSION['msg'] = 'update_periorty_ok';
        } else {
            $_SESSION['errors'] = 'update_periorty_ko';
        }

        redirectToHeader("index.php");
    }
} else if ($_REQUEST['action'] === 'down_rank') {
    if (isset($_REQUEST['id_task']) && is_numeric($_REQUEST['id_task'])) {

        updateRank($dbCo, +1,  $_REQUEST['id_task']);
    }
} else if ($_REQUEST['action'] === 'delete') {
    deleteTask($dbCo);
}
