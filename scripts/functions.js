/**
 * Shows editing task title form (from template ).
 * @param {string} taskTitle the task title. 
 * @param {string} injectedId the id tag of the target injection location.
 */
export function editTasktitle(taskTitle, injectedId) {
    const template = document.getElementById("update-title-task-template")
    let clone = document.importNode(template.content, true);
    clone.getElementById("task-title-textarea").innerText = taskTitle;
    clone.querySelector(".js-task-id").value = injectedId;
    const injected = document.getElementById(injectedId)
    injected.appendChild(clone);

}

/**
 * listen to click to edit btns when click empty container(li) add template(form)
 * @param {array} editButtons lst of editing btn. 
 */
export function listenToEditBtn(editButtons) {

    editButtons.forEach(b => {
        b.addEventListener("click", (e) => {
            let li = e.currentTarget.parentNode;
            let txt = li.querySelector(".js-task-title_txt").innerText;
            li.innerText = '';
            editTasktitle(txt, li.dataset.id);
        });

    });

}


export function dropAndDrop() {
    let todayOL = document.getElementById("today-task-lst");
    let priorityOL = document.getElementById("priority-task-lst");
    let draggableLst = document.querySelectorAll(".js-drage");
    let selected = null;

    // Add dragstart event listener to each draggable item
    draggableLst.forEach(li => {
        li.setAttribute('draggable', true);

        li.addEventListener("dragstart", function (e) {
            selected = e.target;
            e.dataTransfer.effectAllowed = "move";
        });

        li.addEventListener("dragend", function (e) {
            selected = null;
        });
    });

    // Add dragover and drop event listeners to priorityOL
    priorityOL.addEventListener('dragover', function (e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = "move";
    });

    priorityOL.addEventListener('drop', function (e) {
        e.preventDefault();
        if (selected) {
            priorityOL.appendChild(selected);
            //get the number of index in ol starts with 0
            let indexOlPriority = Array.prototype.indexOf.call(priorityOL.children, selected);
            console.log(indexOlPriority);
            selected = null;
        }
    });

    todayOL.addEventListener('dragover', function (e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = "move";
    });

    todayOL.addEventListener('drop', function (e) {
        e.preventDefault();
        if (selected) {
            todayOL.appendChild(selected);
            //get the number of index in ol starts with 0
            let indexOltoday = Array.prototype.indexOf.call(priorityOL.children, selected);
            console.log("the tasks go to the bottom (-1)",indexOltoday);
            selected = null;
            console.log('Dropped into today tasks');
        }
    });

}