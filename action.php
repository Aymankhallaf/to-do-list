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
    addTask($_POST['task_title'], $dbCo);
} 

else if ($_REQUEST['action'] === 'archive') {
    archiveTasks($dbCo);
}


else if ($_REQUEST['action'] === 'edit_task_title'){
    // UPDATE `task` SET `title_task` = 'modify un task' WHERE `task`.`id_task` = 24; 
}