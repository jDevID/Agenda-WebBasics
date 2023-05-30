document.addEventListener('DOMContentLoaded', (event) => {

    let selectedRendezvous = null;
    let selectedRendezvousId = null;

    setInterval(loadRendezvous, 1000);


    function loadRendezvous() {
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log("liste asynchrone rendez-vous -> HTTP status : " + xhr.status);
                    // console.log("Response headers -> " + xhr.getAllResponseHeaders());
                    // console.log("Response text -> " + xhr.responseText);
                    if (xhr.responseXML !== null) {
                        manageRendezvousXMLResponse(xhr.responseXML);
                    } else {
                        console.log("Liste asynchrone rendez-vous -> XML null ou erreur de format");
                    }
                }
            }
        }
        xhr.open("GET", "../controllers/list_rendezvous_async.php", true);
        xhr.send(null);
    }

    // Fonction pour gérer la réponse ajax XML du rendez-vous

    function manageRendezvousXMLResponse(xml) {
        let table = document.getElementById("table_rendezvous_list");
        let newTable = document.createElement('table');
        newTable.id = 'table_rendezvous_list';
        let oldTable = table;
        oldTable.id = '';

        let tr_header = document.createElement("tr");
        let th_id = document.createElement("th");
        let th_clientName = document.createElement("th");
        let th_description = document.createElement("th");
        let th_date = document.createElement("th");
        let th_start_hour = document.createElement("th");
        let th_end_hour = document.createElement("th");

        th_id.innerHTML = "ID";
        th_clientName.innerHTML = "Nom";
        th_description.innerHTML = "Description";
        th_date.innerHTML = "Date";
        th_start_hour.innerHTML = "Heure de début";
        th_end_hour.innerHTML = "Heure de fin";

        tr_header.appendChild(th_id);
        tr_header.appendChild(th_clientName);
        tr_header.appendChild(th_description);
        tr_header.appendChild(th_date);
        tr_header.appendChild(th_start_hour);
        tr_header.appendChild(th_end_hour);

        newTable.appendChild(tr_header);

        let rendezvous = xml.getElementsByTagName("rendezvous");
        for (let i = 0; i < rendezvous.length; i++) {
            let tr_rdv = document.createElement("tr");
            let td_id = document.createElement("td");
            let td_clientName = document.createElement("td");
            let td_description = document.createElement("td");
            let td_date = document.createElement("td");
            let td_start_hour = document.createElement("td");
            let td_end_hour = document.createElement("td");

            let idNode = rendezvous[i].getElementsByTagName("id")[0];
            let clientNameNode = rendezvous[i].getElementsByTagName("clientName")[0];
            let descriptionNode = rendezvous[i].getElementsByTagName("description")[0];
            let dateNode = rendezvous[i].getElementsByTagName("date")[0];
            let startHourNode = rendezvous[i].getElementsByTagName("start_hour")[0];
            let endHourNode = rendezvous[i].getElementsByTagName("end_hour")[0];

            let id = idNode && idNode.firstChild ? idNode.firstChild.nodeValue : "";
            let clientName = clientNameNode && clientNameNode.firstChild ? clientNameNode.firstChild.nodeValue : "";
            let description = descriptionNode && descriptionNode.firstChild ? descriptionNode.firstChild.nodeValue : "";
            let date = dateNode && dateNode.firstChild ? dateNode.firstChild.nodeValue : "";
            let start_hour = startHourNode && startHourNode.firstChild ? startHourNode.firstChild.nodeValue : "";
            let end_hour = endHourNode && endHourNode.firstChild ? endHourNode.firstChild.nodeValue : "";

            td_id.innerHTML = id;
            td_clientName.innerHTML = clientName;
            td_description.innerHTML = description;
            td_date.innerHTML = date;
            td_start_hour.innerHTML = start_hour;
            td_end_hour.innerHTML = end_hour;

            tr_rdv.appendChild(td_id);
            tr_rdv.appendChild(td_clientName);
            tr_rdv.appendChild(td_description);
            tr_rdv.appendChild(td_date);
            tr_rdv.appendChild(td_start_hour);
            tr_rdv.appendChild(td_end_hour);

            tr_rdv.id = id;

            tr_rdv.addEventListener('click', function (event) {
                let currentRendezvousId = id;
                console.log(id);
                if (selectedRendezvous && selectedRendezvous !== this) {
                    selectedRendezvous.style.backgroundColor = '';
                    selectedRendezvous.classList.remove("selected");
                }
                this.style.backgroundColor = 'red';
                this.classList.add("selected");
                selectedRendezvous = this;
                selectedRendezvousId = this.id;

                if (!this.querySelector('#delete-button-rdv')) {
                    let th_action = document.createElement("th");
                    th_action.innerHTML = "Action";
                    tr_header.appendChild(th_action);
                    let newDeleteButton = document.createElement("button-del-rendezvous");
                    newDeleteButton.id = 'delete-button-rdv';
                    newDeleteButton.innerHTML = 'Delete';

                    newDeleteButton.addEventListener('click', function () {
                        newDeleteButton.disabled = true;

                        let xhr = new XMLHttpRequest();
                        xhr.open('POST', '../controllers/delete_rendezvous.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                        xhr.send(`id=${selectedRendezvousId}`);

                        xhr.onload = function () {
                            if (xhr.status === 200) {
                                selectedRendezvous.remove();
                                showToast('Rendezvous supprimé', 'success');
                                selectedRendezvous = null;
                                selectedRendezvousId = null;
                                let th_action = document.querySelector("#table_rendezvous_list th:last-child");

                            } else if (xhr.status === 403) {
                                showToast('403 opération interdite', 'error');
                            } else {
                                showToast(`Erreur lors de la suppression rendezvous avec ID : ${selectedRendezvousId}`, 'error');
                            }

                            newDeleteButton.disabled = false;
                        };
                    });

                    let td_action = document.createElement("td");
                    td_action.appendChild(newDeleteButton);
                    tr_rdv.appendChild(td_action);
                }
            });

            if (id === selectedRendezvousId) {
                tr_rdv.style.backgroundColor = 'red';
                tr_rdv.classList.add("selected");
                selectedRendezvous = tr_rdv;
            }
            newTable.appendChild(tr_rdv);
        }
        oldTable.parentNode.replaceChild(newTable, oldTable);
        if (selectedRendezvous) {
            document.getElementById(selectedRendezvousId).click();
        }
    }

    // Click outside to deselect
    document.addEventListener('click', function (event) {
        let isInsideTable = event.target.closest('tr');
        if (!isInsideTable && selectedRendezvous) {
            selectedRendezvous.style.backgroundColor = '';
            selectedRendezvous.classList.remove("selected");
            selectedRendezvous = null;
            selectedRendezvousId = null;
            let th_action = document.querySelector("#table_rendezvous_list th:last-child");
            if (th_action && th_action.innerHTML === "Action") {
                th_action.remove();
            }
            let td_action = document.querySelector("#table_rendezvous_list td:last-child");
            if (td_action) {
                td_action.remove();
            }
        }
    });

});