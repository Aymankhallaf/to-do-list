<?php
session_start();
include 'include/_connection.php';
include 'include/_function.php';
include 'include/_config.php';

// header('Content-type:application/json');


if ($_REQUEST['action'] === 'archive') {
    if (isset($_REQUEST['id-task']) && is_numeric($_REQUEST['id-task'])) {
        archiveTask($dbCo, $_REQUEST['id-task']);
    }

}
