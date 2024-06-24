<?php
try {
    $dbCo = new PDO(
        'mysql:host=db;dbname=todolist;charset=utf8',
        'php_connection',
        'i3kB!r+$9K";K>a'
    );
    $dbCo->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );
} catch (Exception $e) {
    die('Unable to connect to the database.
' . $e->getMessage());
}

$query = $dbCo->prepare("SELECT title_task, planning_date FROM task;");

$query->execute();

while ($task_title= $query->fetch()) {
    echo '<li>'.$task_title['title_task'].'</li>';
    }