import { listenToEditBtn } from './functions.js'


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


function archive(id) {

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


document.querySelectorAll('[data-archive-id]').forEach(
    (b) => b.addEventListener('click',
        function (e) { archive(b.dataset.archiveId); }));




// async function getFormData() {

//     document.getElementById("add-task").addEventListener('submit',
//         function (e) {
//             e.preventDefault();
//             let newTask = {
//                 action: "add",
//                 myToken: document.getElementById("myToken").value,
//                 titleTask: document.getElementById("task-title-textarea").value,
//                 planningDate: document.getElementById("planning-date").value
//             }
//             console.log(newTask);
//             return newTask

//         });
// }


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
            callApi('PUT', newTask).then(data => {
                if (!data.isOk) {
                    console.log("error api data");
                    return;
                }
                


            })
        })
};

addTask();
