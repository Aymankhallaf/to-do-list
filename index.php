<?php
session_start();
include 'include/_connection.php';
include 'include/_config.php';
include 'include/_function.php';

// Create session token
if (!isset($_SESSION['myToken'])) {
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
  <script type="module" src="scripts/script.js"></script>

  <link href="/style/style.css" rel="stylesheet" />
  <title>TO DO LIST</title>

</head>

<body token="">

  <?php

  echo getHtmlMessages($messages);

  echo getHtmlErrors($errors);

  ?>

  <main class="main">
    <h1 class="main-title">TO DO LIST</h1>
    <a aria-label="notification" class="dropdown">
      <button class="dropbtn notif"><?php echo count(getTodayTask($dbCo)); ?></button>
      <ul class="dropdown-content">
        <?php
        $todayNotif = getTodayTask($dbCo);
        echo (showTasktitle($todayNotif)); ?>
      </ul>
    </a>


    <form id="add-task" class="border-container write-task-form">
      <input type="hidden" id="myToken" name="myToken" value="<?= $_SESSION['myToken'] ?>">
      <input type="hidden" name="action" value="insert">
      <textarea rows="auto" cols="100%" type="text" class="write-task-title" id="task-title-textarea" name="task_title" required></textarea>
      <label for="planning-date">Planning date:</label>
      <input id="planning-date" type="date" value="2024-07-04" name="planning-date" />
      <button id="submit-task" type="submit"><img src="/img/add.svg" alt="add task"></button>
    </form>




    <h2 class="priority-task">Priority tasks</h2>
    <ol id="priority-task-lst" class="priority-task-lst">
      <?php

      $priorityTasks = getPriorityTasks($dbCo);
      echo (showLsTasks($priorityTasks));
      ?>

    </ol>
    <h2 class="today-task">Today’s tasks</h2>
    <ol id="today-task-lst" class="task-lst">
      <?php
      $nonTerminatedTasks = getNonTerminatedTask($dbCo);
      echo (showLsTasks($nonTerminatedTasks));

      ?>


    </ol>

    <h2 class="today-task">Terminated tasks</h2>
    <ol id="terminated-tasks" class="terminated-tasks">

      <?php
      $terminatedTasks = getTerminatedTask($dbCo);
      echo (addHtmlTags($terminatedTasks));

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
      <label for="start">Planning date:</label>
      <input value="" type="date" name="Planning_date" class="js-Planning_date" />
      <button type="submit"><img src="/img/add.svg" alt="edit task"></button>
    </form>
  </template>

  <template id="add-title-task-template">
    <form class="edit-task-form">
      <input type="hidden" name="myToken" value="<?= $_SESSION['myToken'] ?>">
      <input type="hidden" name="action" value="edit">
      <input class="js-task-id" type="hidden" name="task_id" value="">
      <textarea rows="auto" cols="100%" type="text" class="edit-task-title" id="task-title-textarea" name="task_title" required></textarea>
      <label for="start">Planning date:</label>
      <input value="" type="date" name="Planning_date" class="js-Planning_date" />
      <button type="submit"><img src="/img/add.svg" alt="edit task"></button>
    </form>
  </template>



  <template id="show-task-template">
    <li data-id="" class="border-container task-lst-item js-drage" draggable="true">
      <label class="hide task-lst-item-done" for="done" draggable="false">done</label>
      <input role="checkbox" class="task-lst-item-checkbox" type="checkbox" name="done" value="1" draggable="false">
      <p class="js-task-title-txt" draggable="false"></p>
      <time value="" class="js-planning-date" datetime=""></time>
      <button class="js-archive" data-archive-id='' draggable="false">
        <img aria-hidden="true" src="/img/archive.svg" alt="archive task" draggable="false"></button>
    </li>
    <template>

</body>

</html>