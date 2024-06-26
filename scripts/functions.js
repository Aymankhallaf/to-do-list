/**
 * add task title form (from template ).
 * @param {string} taskTitle the task title. 
 * @param {string} injectedId the id tag of the target injection location.
 */
export function addTasktitle(taskTitle,injectedId) {
    const template = document.getElementById("add-title-task-template")
    let clone = document.importNode(template.content, true);
    clone.getElementById("task-title-textarea").innerText = taskTitle;
    const injected=document.getElementById(injectedId)
    injected.appendChild(clone);

}

