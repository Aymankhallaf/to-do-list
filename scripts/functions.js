/**
 * Shows editing task title form (from template ).
 * @param {string} taskTitle the task title.
 * @param {string} PlanningDate the planning date. 
 * @param {string} injectedId the id tag of the target injection location.
 */
export function editTask(taskTitle, PlanningDate,injectedId) {
    const template = document.getElementById("update-title-task-template");
    let clone = document.importNode(template.content, true);
    clone.getElementById("task-title-textarea").innerText = taskTitle;
    const [day, month, year] = PlanningDate.split('/');
    const formattedDate = `${year}-${month}-${day}`;
    clone.getElementById("Planning_date").value = formattedDate;
    clone.querySelector(".js-task-id").value = injectedId;
    const injected = document.getElementById(injectedId);
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
            let PlanningDate = li.querySelector('.js-planning-date').dateTime;
            li.innerText = '';
            editTask(txt,PlanningDate, li.dataset.id);
        });

    });

}


