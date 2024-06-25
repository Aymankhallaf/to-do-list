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
