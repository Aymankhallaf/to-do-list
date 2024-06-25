<?php
include 'include/_connection.php';
include 'include/_function.php';


$query = $dbCo->prepare("SELECT title_task FROM task;");

$query->execute();

// while ($task_title= $query->fetch()) {
//     echo '<li>'.$task_title['title_task'].'</li>';
//     }

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
    <form class="border-container write-task-form">
      <label class="hide" for="write-task-title"> new task </label>
      <input type="text" class="write-task-title task-title" id="task_title" name="task_title">
      <button type="submit"><img src="/img/add.svg" alt="add task"></button>
    </form>


    <h2 class="today-task">Today’s tasks</h2>



    <ol class="task-lst">

      <?php
      echo (showLsTasks($query->fetchAll()));
      ?>

      <li class="task-lst-item">
        <label class="hide task-lst-item-done" for="done">done </label>
        <input class="task-lst-item-checkbox" type="checkbox" id="done" name="done">
        <input type="text" size="50" name="task">show tasks list by name</input>
        <button class="task-edit" type="submit"><img src="/img/edit.svg" aria-hidden="true" alt="edit"></button>
        <button class="task-delete" type="submit"><img src="/img/delete.svg" aria-hidden="true" alt="delete"></button>
      </li>
      <li class="task-lst-item">
        <label class="hide task-lst-item-done" for="done"> done </label>
        <input class="task-lst-item-checkbox" type="checkbox" id="done" name="done">
        <p>show tasks list by name</p>
        <button class="task-edit" type="submit"><img src="/img/edit.svg" alt="edit task"></button>
        <button class="task-delete" type="submit"><img src="/img/delete.svg" alt="delete task"></button>
      </li>
      <li class="task-lst-item">
        <label class="hide task-lst-item-done" for="done"> done </label>
        <input class="task-lst-item-checkbox" type="checkbox" id="done" name="done">
        <p>show tasks list by name</p>
        <button class="task-edit" type="submit"><img src="/img/edit.svg" alt="edit"></button>
        <button class="task-delete" type="submit"><img src="/img/delete.svg" alt="delete"></button>
      </li>
    </ol>



  </main>

  <footer>
    <p>© 2024 To Do List</p>
</body>

</html>