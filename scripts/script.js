import { listenToEditBtn, dropAndDrop  } from './functions.js'


const editButtons = document.querySelectorAll(".js-edit-task-title");

listenToEditBtn(editButtons);

dropAndDrop();