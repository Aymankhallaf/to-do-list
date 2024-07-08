import { listenToEditBtn } from './functions.js'


const editButtons = document.querySelectorAll(".js-edit-task-title");

listenToEditBtn(editButtons);
console.log();


async function apiArchive(param) {
    try {
        const response = await fetch("api.php?" + param);
        const dataResponse = await response.json();


        document.getElementById("terminated-tasks").prepend(document.querySelector("[data-id='" + dataResponse.id + "']"));

    }
    catch (error) {
        console.error("Unable to load datas from server : " + error);

    }

}


document.querySelectorAll('[data-archive-id]').forEach(
    (b) => b.addEventListener('click',
        function (e) { apiArchive("action=archive&id-task=" + b.dataset.archiveId + "&token=" + document.getElementById("myToken").value); }));

