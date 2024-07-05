<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();
try {
    $dbCo = new PDO(
      "mysql:host=" . $_ENV["DB_HOST"] . ";dbname=todolist;charset=utf8",
      $_ENV["DB_USER"],
      $_ENV["DB_PWD"]
    );
    $dbCo->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );
} catch (Exception $e) {
    die('Unable to connect to the database.
' . $e->getMessage());
}
