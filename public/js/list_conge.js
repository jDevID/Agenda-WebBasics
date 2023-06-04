document.addEventListener('DOMContentLoaded', (event) => {
    let selectedConge;
    let selectedCongeId;
    setInterval(loadConges, 3000);

    let switchButton = document.getElementById('switchButton');


    if (switchButton) {
        switchButton.addEventListener('click', switchView);
    }

    function switchView() {
        let userRole = switchButton.dataset.role;
        let rowRdv = document.getElementById('rowRdv');
        let rowConge = document.getElementById('rowConge');


        if (rowRdv.style.display === "none") {
            rowRdv.style.display = "flex";
            rowConge.style.display = "none";
            switchButton.innerText = "Congés";
        } else {
            rowRdv.style.display = "none";
            rowConge.style.display = "flex";
            switchButton.innerText = "Rendez-vous";
        }
    }


    function loadConges() {
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    //console.log("Liste asynchrone congé -> HTTP status : " + xhr.status);
                    if (xhr.responseXML !== null) {
                        manageCongeXMLResponse(xhr.responseXML);
                    } else {
                        console.log("Liste asynchrone congé -> XML null ou erreur de format");
                    }
                }
            }
        }
        xhr.open("GET", "../controllers/list_conge_async.php", true);
        xhr.send(null);
    }


    function manageCongeXMLResponse(xml) {
        let table = document.getElementById("table_conge_list");
        let newTable = document.createElement('table');
        newTable.id = 'table_conge_list';
        let oldTable = table;
        oldTable.id = '';

        let tr_header = document.createElement("tr");
        let th_id = document.createElement("th");
        let th_date = document.createElement("th");

        th_id.innerHTML = "ID";
        th_date.innerHTML = "Date";

        tr_header.appendChild(th_id);
        tr_header.appendChild(th_date);

        newTable.appendChild(tr_header);

        let conges = xml.getElementsByTagName("conge");
        for (let i = 0; i < conges.length; i++) {
            let tr_conge = document.createElement("tr");
            let td_id = document.createElement("td");
            let td_date = document.createElement("td");

            let idNode = conges[i].getElementsByTagName("id")[0];
            let dateNode = conges[i].getElementsByTagName("date")[0];

            let id = idNode && idNode.firstChild ? idNode.firstChild.nodeValue : "";
            let date = dateNode && dateNode.firstChild ? dateNode.firstChild.nodeValue : "";

            td_id.innerHTML = id;
            td_date.innerHTML = date;

            tr_conge.appendChild(td_id);
            tr_conge.appendChild(td_date);

            tr_conge.id = id;

            tr_conge.addEventListener('click', function (event) {
                let currentCongeId = id;
                if (selectedConge && selectedConge !== this) {
                    selectedConge.style.backgroundColor = '';
                    selectedConge.classList.remove("selected");
                }
                this.style.backgroundColor = 'red';
                this.classList.add("selected");
                selectedConge = this;
                selectedCongeId = this.id;

                if (!this.querySelector('#delete-button-conge')) {
                    let th_action = document.createElement("th");
                    th_action.innerHTML = "Action";
                    tr_header.appendChild(th_action);
                    let newDeleteButton = document.createElement("button-del-conge");
                    newDeleteButton.id = 'delete-button-conge';
                    newDeleteButton.innerHTML = 'Delete';

                    newDeleteButton.addEventListener('click', function () {
                        newDeleteButton.disabled = true;

                        let xhr = new XMLHttpRequest();
                        xhr.open('POST', '../controllers/delete_conge.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                        xhr.send(`id=${selectedCongeId}`);

                        xhr.onload = function () {
                            let responseMessage = JSON.parse(xhr.responseText).message;
                            if (xhr.status === 200) {
                                selectedConge.remove();
                                showToast(responseMessage, 'success');
                                selectedConge = null;
                                selectedCongeId = null;
                            } else if (xhr.status === 403) {
                                showToast(responseMessage, 'error');
                            } else {
                                showToast(responseMessage, 'error');
                            }

                            newDeleteButton.disabled = false;
                        };
                    });

                    let td_action = document.createElement("td");
                    td_action.appendChild(newDeleteButton);
                    tr_conge.appendChild(td_action);
                }
            });

            if (id === selectedCongeId) {
                tr_conge.style.backgroundColor = 'red';
                tr_conge.classList.add("selected");
                selectedConge = tr_conge;
            }
            newTable.appendChild(tr_conge);
        }
        oldTable.parentNode.replaceChild(newTable, oldTable);
        if (selectedConge) {
            document.getElementById(selectedCongeId).click();
        }
    }

// Click vide pour deselect
    document.addEventListener('click', function (event) {
        let isInsideTable = event.target.closest('tr');
        if (!isInsideTable && selectedConge) {
            selectedConge.style.backgroundColor = '';
            selectedConge.classList.remove("selected");
            selectedConge = null;
            selectedCongeId = null;
            let th_action = document.querySelector("#table_conge_list th:last-child");
            if (th_action && th_action.innerHTML === "Action") {
                th_action.remove();
            }
            let td_action = document.querySelector("#table_conge_list td:last-child");
            if (td_action) {
                td_action.remove();
            }
        }
    });
});




