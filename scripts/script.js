import { addTasktitle } from './functions.js'

addTasktitle('', "add-task-container");

const editButtons = document.querySelectorAll(".js-edit-task-title");

editButtons.forEach(b => {
    b.addEventListener("click", (e) => {
        let li = e.currentTarget.parentNode;
        let txt = li.querySelector(".js-task-title_txt").innerText;
        console.log(li.dataset.id);
        e.currentTarget.parentNode.classList.toggle("hide");
        document.getElementById("add-task-container").innerText='';
        addTasktitle(txt,  "add-task-container");

    });

});


