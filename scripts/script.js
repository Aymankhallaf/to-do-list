import { listenToEditBtn  } from './functions.js'


const editButtons = document.querySelectorAll(".js-edit-task-title");

listenToEditBtn(editButtons);

// dropAndDrop();


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
            priorityOL.insertBefore(selected, priorityOL.firstChild);
            console.log(selected.id);
            //set cookies
            // document.cookie = "id_task="+selected.id+";"+"rank_task="+indexOlPriority;

            let fd = new FormData();
            fd.append("task_id", selected.id)
            fetch("index.php", {
                method: "POST",
                body: fd
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text) });
                }
                return response.json();
            })
            .then(json => {
                console.log('Server response:', json);
            })
            .catch(error => {
                console.error('Error:', error);
            });
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
            console.log("the tasks go to the bottom (-1)", indexOltoday);
            selected = null;
        }
    });

