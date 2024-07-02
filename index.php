<?php
session_start();
include 'include/_connection.php';
include 'include/_config.php';
include 'include/_function.php';


var_dump($_GET);

//create session token
if (!isset(($_SESSION['myToken']))) {

  $_SESSION['myToken'] = md5(uniqid(mt_rand(), true));
}




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

  <?php


  echo getHtmlMessages($messages);

  echo getHtmlErrors($errors);

  ?>

  <main class="main">
    <h1 class="main-title">TO DO LIST</h1>


    <form class="border-container write-task-form" action="action.php" method="post">
      <input type="hidden" name="myToken" value="<?= $_SESSION['myToken'] ?>">
      <input type="hidden" name="action" value="insert">
      <textarea rows="auto" cols="100%" type="text" class="write-task-title" id="task-title-textarea" name="task_title" required></textarea>
      <button type="submit"><img src="/img/add.svg" alt="add task"></button>
    </form>



    <h2 class="priority-task">Priority tasks</h2>
    <p># Drag and drop to set the priority tasks </p>
    <ol id="priority-task-lst" class="priority-task-lst">
    <?php
      $priorityTasks = getPriorityTasks($dbCo);
      echo (showLsTasks($priorityTasks->fetchAll()));
      ?>

    </ol>

      <h2 class="today-task">Today’s tasks</h2>
    <ol id="today-task-lst" class="task-lst">

      <?php
      $nonTerminatedTasks = getNterminatedNpriority($dbCo);
      echo (showLsTasks($nonTerminatedTasks->fetchAll()));
      ?>

    </ol>



  </main>

  <footer class="footer">
    <p>© 2024 To Do List</p>
  </footer>

  <template id="update-title-task-template">
    <form class="edit-task-form" action="action.php" method="post">
      <input type="hidden" name="myToken" value="<?= $_SESSION['myToken'] ?>">
      <input type="hidden" name="action" value="edit">
      <input class="js-task-id" type="hidden" name="task_id" value="">
      <textarea rows="auto" cols="100%" type="text" class="edit-task-title" id="task-title-textarea" name="task_title" required></textarea>
      <button type="submit"><img src="/img/add.svg" alt="edit task"></button>
    </form>
  </template>

  <script type="module" src="scripts/script.js"></script>

</body>

</html>