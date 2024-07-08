import { listenToEditBtn } from './functions.js'


const editButtons = document.querySelectorAll(".js-edit-task-title");

listenToEditBtn(editButtons);


let archives = document.querySelectorAll(".js-archive");
console.log(archives);


async function apiArchive(param) {
    try {
        const response  = await fetch("api.php?"+param);
        const dataResponse = await response.json(); 
        console.log(dataResponse);


    }
    catch (error) {

    }

}


document.querySelectorAll('[data-archive-id]').forEach(
    (b) => b.addEventListener('click',
        function (e) { apiArchive("action=archive&id-task="+b.dataset.archiveId+"&token="+document.getElementById("myToken").value); }));

