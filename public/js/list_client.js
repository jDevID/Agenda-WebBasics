document.addEventListener('DOMContentLoaded', (event) => {

    let deleteButton = document.createElement("button");
    deleteButton.id = 'delete-button-client';
    deleteButton.style.display = 'none';
    deleteButton.innerHTML = 'Delete';
    document.body.appendChild(deleteButton);

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
                        console.log("Liste asynchrone client: xhr.responseXML is null");
                    }
                }
            }
        }
        xhr.open("GET", "../controllers/list_client_async.php", true);
        xhr.send(null);
    }

    function manageClientXMLResponse(xml) {
        let table = document.getElementById("table_client_list");
        table.innerHTML = "";

        let tr_header = document.createElement("tr");
        let th_id = document.createElement("th");
        let th_name = document.createElement("th");

        th_id.innerHTML = "ID";
        th_name.innerHTML = "Username";

        tr_header.appendChild(th_id);
        tr_header.appendChild(th_name);

        table.appendChild(tr_header);

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
                deleteButton.style.display = '';
                selectedClient = this;
                selectedClientId = this.id;
            });

            if (id === selectedClientId) {
                tr_client.style.backgroundColor = 'red';
                tr_client.classList.add("selected");
                deleteButton.style.display = '';
                selectedClient = tr_client;
            }

            table.appendChild(tr_client);
        }
    }

    deleteButton.addEventListener('click', function () {
        if (selectedClient) {
            let xhr = new XMLHttpRequest();
            xhr.open('POST', '../controllers/delete_client.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send(`id=${selectedClientId}`);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    // Remove the client from the table
                    selectedClient.remove();
                    selectedClient = null;
                    selectedClientId = null;
                    deleteButton.style.display = 'none';
                } else if (xhr.status === 403) {
                    console.error('Impossible de se supprimer sois-même');
                } else {
                    console.error(`Erreur lors de la suppression client avec ID : ${selectedClientId}`);
                }
            };
        }
    });

    document.addEventListener('click', function (event) {
        let isInsideTable = event.target.closest('tr');
        if (!isInsideTable && selectedClient) {
            selectedClient.style.backgroundColor = '';
            selectedClient.classList.remove("selected");
            deleteButton.style.display = 'none';
            selectedClient = null;
            selectedClientId = null;
        }
    });
});