export function addTasktitle(taskTitle,injectedId) {
    const template = document.getElementById("add-title-task-template")
    let clone = document.importNode(template.content, true);
    clone.getElementById("task-title-textarea").innerText =taskTitle;
    const injected=document.getElementById(injectedId)
    injected.appendChild(clone);

}