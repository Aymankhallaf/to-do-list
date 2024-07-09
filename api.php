<?php
session_start();
include 'include/_connection.php';
include 'include/_function.php';
include 'include/_config.php';

// header('Content-s:application/json');
$inputData = json_decode(file_get_contents('php://input'), true);

if ($inputData['action'] === 'archive'&& $_SERVER['REQUEST_METHOD']==='PUT') {
    if (isset($inputData['idTask']) && is_numeric($inputData['idTask'])) {
        archiveTask($dbCo, $inputData['idTask']);
    }

}

if ($inputData['action'] === 'add'&& $_SERVER['REQUEST_METHOD']==='PUT') {

    addTask($dbCo,$inputData );
}
