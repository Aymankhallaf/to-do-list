<?php
include 'include/_connection.php';
include 'include/_function.php';

session_start();

$query = $dbCo->prepare("SELECT title_task FROM task ORDER BY creation_date DESC;");

$query->execute();



?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
  <link href="/style/style.css" rel="stylesheet" />

  <title>TO DO LIST</title>


</head>

<body>
  <main class="main">
    <h1 class="main-title">TO DO LIST</h1>
    <form class="border-container write-task-form" method="post">
      <label class="hide write-task-title" for="write-task-label">new task</label>
      <textarea rows="auto" cols="auto" type="text" class="write-task-title" id="task_title" name="task_title"></textarea>
      <button type="submit"><img src="/img/add.svg" alt="add task"></button>
    </form>

    <h2 class="today-task">Today’s tasks</h2>



    <ol class="task-lst">

      <?php
      echo (showLsTasks($query->fetchAll()));
      ?>
      <input type="hidden" name="token" value="<?= $_SESSION['myToken']?>">
      <?php var_dump($_SESSION['myToken']); ?>
      
    </ol>



  </main>

  <footer>
    <p>© 2024 To Do List</p>
</body>

</html>