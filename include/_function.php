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
        <p>' . $task['title_task'] . '</p> <button class="task-edit" type="submit" role="edit task"><img aria-hidden="true" src="/img/edit.svg" alt="edit task"></button>
        <button class="task-delete" type="submit" role="delete task"><img aria-hidden="true" src="/img/delete.svg" alt="delete task"></button>
      </li>';
    }
    return $li;
}
