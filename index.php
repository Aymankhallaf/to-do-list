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


$nonTerminatedTasks = getDataFromDatabase($dbCo);


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

    <div id="add-task-container" >

    </div>


    <h2 class="today-task">Today’s tasks</h2>



    <ol class="task-lst">

      <?php
      echo (showLsTasks($nonTerminatedTasks->fetchAll()));
      ?>

    </ol>



  </main>

  <footer>
    <p>© 2024 To Do List</p>
  </footer>

  <template id="add-title-task-template">
    <form class="border-container write-task-form" action="action.php" method="post">
      <input type="hidden" name="myToken" value="<?= $_SESSION['myToken'] ?>">
      <input type="hidden" name="action" value="insert">
      <label class="hide write-task-title" for="write-task-label">new task</label>
      <textarea rows="auto" cols="auto" type="text" class="write-task-title" id="task-title-textarea" name="task_title" required></textarea>
      <button type="submit"><img src="/img/add.svg" alt="add task"></button>
    </form>
  </template>
  <script type="module" src="scripts/script.js"></script>

</body>

</html>