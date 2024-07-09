import { listenToEditBtn, addTaskHtml, archive } from './_functions.js'


const editButtons = document.querySelectorAll(".js-edit-task-title");

listenToEditBtn(editButtons);




async function callApi(method, param) {
    try {
        const response = await fetch("api.php",
            {
                method: method,
                body: JSON.stringify(param),
                headers: {
                    'Content-type': 'application/json'
                }
            });
        return await response.json();



    }
    catch (error) {
        console.error("Unable to load datas from server : " + error);

    }

}



document.querySelectorAll('[data-archive-id]').forEach(
    (b) => b.addEventListener('click',
        function (e) { archive(b.dataset.archiveId); }));



function addTask() {
    document.getElementById("add-task").addEventListener('submit',
        function (e) {
            e.preventDefault();
            let newTask = {
                action: "add",
                myToken: document.getElementById("myToken").value,
                titleTask: document.getElementById("task-title-textarea").value,
                planningDate: document.getElementById("planning-date").value
            }
            callApi('POST', newTask).then(data => {
                if (!data.isOk) {
                    console.log("error api data");
                    return;
                }
                document.getElementById("task-title-textarea").innerText="";
                addTaskHtml(data);


            })
        })
};

addTask();
