/**
 * Shows editing task title form (from template ).
 * @param {string} taskTitle the task title.
 * @param {string} PlanningDate the planning date. 
 * @param {string} injectedId the id tag of the target injection location.
 */
export function editTask(taskTitle, PlanningDate, injectedId) {
    const template = document.getElementById("update-title-task-template");
    let clone = document.importNode(template.content, true);
    clone.getElementById("task-title-textarea").innerText = taskTitle;
    const [day, month, year] = PlanningDate.split('/');
    const formattedDate = `${year}-${month}-${day}`;
    clone.querySelector(".js-Planning_date").value = formattedDate;
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
            let txt = li.querySelector(".js-task-title-txt").innerText;
            let PlanningDate = li.querySelector('.js-planning-date').dateTime;
            li.innerText = '';
            editTask(txt, PlanningDate, li.dataset.id);
        });

    });

}




export function addTaskHtml(task) {
    const template = document.getElementById("show-task-template");
    let clone = document.importNode(template.content, true);
    clone.querySelector(".js-task-title-txt").innerText = task["taskTitle"];
    clone.querySelector(".js-planning-date").value = task["taskTitle"];
    clone.querySelector(".js-planning-date").datetime = task["planningDate"];
    clone.querySelector("[data-id]").dataId = task["idTask"];
    const injected = document.getElementById("today-task-lst");
    injected.prepend(clone);

}




export function archive(id) {

    callApi('PUT', {
        action: 'archive',
        idTask: id,
        token: document.getElementById("myToken").value
    })
        .then(data => {
            if (!data.isOk) {
                console.log("error api data");
                return;
            }
            if (data.archive == "1") {

                document.getElementById("terminated-tasks").prepend(document.querySelector("[data-id='" + data.id + "']"));
            }
            else if (data.archive == "0") {
                document.getElementById("priority-task-lst").prepend(document.querySelector("[data-id='" + data.id + "']"));
            }


        })
}
