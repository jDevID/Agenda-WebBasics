document.addEventListener('DOMContentLoaded', (event) => {

    let selectedClient = null;
    let selectedClientId = null;

    setInterval(loadClients, 1000);

    function loadClients() {
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log("Liste asynchrone client -> HTTP status : " + xhr.status);
                    if (xhr.responseXML !== null) {
                        manageClientXMLResponse(xhr.responseXML);
                    } else {
                        console.log("Liste asynchrone client -> XML null ou erreur de format");
                    }
                }
            }
        }
        xhr.open("GET", "../controllers/list_client_async.php", true);
        xhr.send(null);
    }

    // Fonction pour gérer la réponse ajax XML du client
    function manageClientXMLResponse(xml) {
        let table = document.getElementById("table_client_list");
        let newTable = document.createElement('table');
        newTable.id = 'table_client_list';
        let oldTable = table;
        oldTable.id = '';

        let tr_header = document.createElement("tr");
        let th_id = document.createElement("th");
        let th_name = document.createElement("th");

        th_id.innerHTML = "ID";
        th_name.innerHTML = "Nom";

        tr_header.appendChild(th_id);
        tr_header.appendChild(th_name);

        newTable.appendChild(tr_header);

        let clients = xml.getElementsByTagName("client");
        for (let i = 0; i < clients.length; i++) {
            let tr_client = document.createElement("tr");
            let td_id = document.createElement("td");
            let td_name = document.createElement("td");

            let idNode = clients[i].getElementsByTagName("id")[0];
            let nameNode = clients[i].getElementsByTagName("username")[0];

            let id = idNode && idNode.firstChild ? idNode.firstChild.nodeValue : "";
            let name = nameNode && nameNode.firstChild ? nameNode.firstChild.nodeValue : "";

            td_id.innerHTML = id;
            td_name.innerHTML = name;

            tr_client.appendChild(td_id);
            tr_client.appendChild(td_name);
            tr_client.id = id;

            tr_client.addEventListener('click', function (event) {
                let currentClientId = id;
                if (selectedClient && selectedClient !== this) {
                    selectedClient.style.backgroundColor = '';
                    selectedClient.classList.remove("selected");
                }
                this.style.backgroundColor = 'red';
                this.classList.add("selected");

                selectedClient = this;
                selectedClientId = this.id;

                if (!this.querySelector('#delete-button-client')) {
                    let th_action = document.createElement("th");
                    th_action.innerHTML = "Action";
                    tr_header.appendChild(th_action);
                    let newDeleteButton = document.createElement("button");
                    newDeleteButton.id = 'delete-button-client';
                    newDeleteButton.innerHTML = 'Delete';

                    newDeleteButton.addEventListener('click', function () {
                        newDeleteButton.disabled = true;

                        let xhr = new XMLHttpRequest();
                        xhr.open('POST', '../controllers/delete_client.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.send(`id=${selectedClientId}`);

                        xhr.onload = function () {
                            if (xhr.status === 200) {
                                // Retirer le client de la table
                                selectedClient.remove();
                                showToast('Client supprimé', 'success');
                                selectedClient = null;
                                selectedClientId = null;
                                let th_action = document.querySelector("#table_client_list th:last-child");

                            } else if (xhr.status === 403) {
                                showToast('Impossible de se supprimer sois-même', 'error');
                            } else {
                                showToast(`Erreur lors de la suppression client avec ID : ${selectedClientId}`, 'error');
                            }

                            newDeleteButton.disabled = false;
                        };
                    });

                    let td_action = document.createElement("td");
                    td_action.appendChild(newDeleteButton);
                    tr_client.appendChild(td_action);
                }
            });

            if (id === selectedClientId) {
                tr_client.style.backgroundColor = 'red';
                tr_client.classList.add("selected");
                selectedClient = tr_client;
            }
            newTable.appendChild(tr_client);
        }
        oldTable.parentNode.replaceChild(newTable, oldTable);
        if (selectedClient) {
            document.getElementById(selectedClientId).click();
        }
    }


    // Click vide pour désélectionner
    document.addEventListener('click', function (event) {
        let isInsideTable = event.target.closest('tr');
        if (!isInsideTable && selectedClient) {
            selectedClient.style.backgroundColor = '';
            selectedClient.classList.remove("selected");
            selectedClient = null;
            selectedClientId = null;
            let th_action = document.querySelector("#table_client_list th:last-child");
            if (th_action.innerHTML === "Action") {
                th_action.remove();
            }
            let td_action = document.querySelector("#table_client_list td:last-child");
            if (td_action) {
                td_action.remove();
            }
        }
    });
});
