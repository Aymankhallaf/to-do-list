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
} 

else if ($_REQUEST['action'] === 'archive') {
    archiveTask($dbCo);
}


else if ($_REQUEST['action'] === 'edit'){
    editTasktitle($dbCo);
}

else if ($_REQUEST['action'] === 'up_rank'){
    upTaskRank($dbCo);    
}

else if ($_REQUEST['action'] === 'down_rank'){
    downTaskRank($dbCo);    
}

