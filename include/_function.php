<?php

/**
 * show all the tasks in an array
 *
 * @param array $lsttasks a list of tasks
 * @return string string of html tages + task
 */
function showLsTasks(array $lsttasks)
{

    $li = '';
    foreach ($lsttasks as $task) {
        $li .= '<li class="border-container task-lst-item">
        <label class="hide task-lst-item-done" for="done"> done </label>
        <input role="checkbox if the task has been done" class="task-lst-item-checkbox" type="checkbox" id="done" name="done">
        <p>' . $task['title_task'] . '</p> <button class="task-edit" type="submit" role="edit-task"><img aria-hidden="true" src="/img/edit.svg" alt="edit task"></button>
        <button class="task-delete" type="submit" role="delete-task"><img aria-hidden="true" src="/img/delete.svg" alt="delete task"></button>
      </li>';
    }
    return $li;
}


/* insert request */
// insert into task (title_task, creation_date) values ('Mnteger ac neque. Duis bibendum. Morbi non quam nec dui luctus rutrum. Nulla tellus. In sagittis dui vel nisl. Duis ac nibh. Fusce lacus purus, aliquet at, feugiat non, ', '2024-01-06 19:13:47');

// $insert_task = $dbCo->prepare("insert into task 
// (title_task, creation_date) values 
// (:task_title, CURRENT_DATE())");


//     $insert_task->execute([
//         ':task_title' => 'Mnteger ac neque. Duis bibendum. Morbi non quam nec dui luctus rutrum.'
//     ]);



<?php
$postTaskTitle = $_POST['task_title'];
$_SESSION['myToken'] = md5(uniqid(mt_rand(), true));
var_dump($_SESSION['myToken']);
//
if (!empty($_POST)) {
  if (isset($_SERVER) && str_contains($_SERVER['HTTP_REFERER'], 'http://localhost:8080')) {
    var_dump("comes from our server connection");
    if (
      isset($postTaskTitle) && strlen($postTaskTitle) > 0
    ) {

      $insertTask = $dbCo->prepare("INSERT INTO task 
    (title_task, creation_date) VALUES 
    (:task_title, CURRENT_DATE())");


      $bindValue = ([
        ':task_title' => htmlspecialchars($postTaskTitle)
      ]);

      $isnsertOK = $insertTask->execute($bindValue);
      $nb = $insertTask->rowCount();
      var_dump($nb);



      if (strlen($postTaskTitle) > 255) {
        var_dump("it is too long, plz write a shorter task");
      }
    }
  }
}

?>
    
