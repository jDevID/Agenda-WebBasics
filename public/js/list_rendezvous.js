document.addEventListener('DOMContentLoaded', (event) => {

    let deleteButton = document.createElement("button");
    deleteButton.id = 'delete-button';
    deleteButton.style.display = 'none';
    deleteButton.innerHTML = 'Delete';
    document.body.appendChild(deleteButton);

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
                        console.log("list_rendezvous.js -> xhr.responseXML is null");
                    }
                }
            }
        }
        xhr.open("GET", "../controllers/list_rendezvous_async.php", true);
        xhr.send(null);
    }


    function manageRendezvousXMLResponse(xml) {
        let table = document.getElementById("table_rendezvous_list");
        table.innerHTML = "";

        let tr_header = document.createElement("tr");
        let th_id = document.createElement("th");
        let th_clientName = document.createElement("th");
        let th_description = document.createElement("th");
        let th_date = document.createElement("th");
        let th_start_hour = document.createElement("th");
        let th_end_hour = document.createElement("th");

        th_id.innerHTML = "ID";
        th_clientName.innerHTML = "Client Name";
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

        table.appendChild(tr_header);

        let rendezvous = xml.getElementsByTagName("rendezvous");
        for (let i = 0; i < rendezvous.length; i++) {

            let idNode = rendezvous[i].getElementsByTagName("id")[0];
            let rendezvousId = idNode && idNode.firstChild ? idNode.firstChild.nodeValue : "";


            let tr_rdv = document.createElement("tr");
            tr_rdv.id = rendezvousId;
            let td_id = document.createElement("td");
            td_id.innerHTML = rendezvousId;
            let td_clientName = document.createElement("td");
            let td_description = document.createElement("td");
            let td_date = document.createElement("td");
            let td_start_hour = document.createElement("td");
            let td_end_hour = document.createElement("td");


            let clientNameNode = rendezvous[i].getElementsByTagName("clientName")[0];
            let descriptionNode = rendezvous[i].getElementsByTagName("description")[0];
            let dateNode = rendezvous[i].getElementsByTagName("date")[0];
            let startHourNode = rendezvous[i].getElementsByTagName("start_hour")[0];
            let endHourNode = rendezvous[i].getElementsByTagName("end_hour")[0];

            let clientName = clientNameNode && clientNameNode.firstChild ? clientNameNode.firstChild.nodeValue : "";
            let description = descriptionNode && descriptionNode.firstChild ? descriptionNode.firstChild.nodeValue : "";
            let date = dateNode && dateNode.firstChild ? dateNode.firstChild.nodeValue : "";
            let start_hour = startHourNode && startHourNode.firstChild ? startHourNode.firstChild.nodeValue : "";
            let end_hour = endHourNode && endHourNode.firstChild ? endHourNode.firstChild.nodeValue : "";

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
            table.appendChild(tr_rdv);


            tr_rdv.addEventListener('click', function (event) {
                let currentRendezvousId = rendezvousId;
                if (selectedRendezvous && selectedRendezvous !== this) {
                    selectedRendezvous.style.backgroundColor = '';
                    selectedRendezvous.classList.remove("selected");
                }
                this.style.backgroundColor = 'red';
                this.classList.add("selected");
                deleteButton.style.display = '';
                selectedRendezvous = this;
                selectedRendezvousId = this.id;
            });


            if (rendezvousId === selectedRendezvousId) {
                tr_rdv.style.backgroundColor = 'red';
                tr_rdv.classList.add("selected");
                deleteButton.style.display = '';
                selectedRendezvous = tr_rdv;
            }

        }
    }


    deleteButton.addEventListener('click', function () {
        if (selectedRendezvous && selectedRendezvous.isConnected) {
            let xhr = new XMLHttpRequest();
            xhr.open('POST', '../controllers/delete_rendezvous.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send(`id=${selectedRendezvousId}`);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    // selectedRendezvous.remove();
                    selectedRendezvous = null;
                    selectedRendezvousId = null;
                    deleteButton.style.display = 'none';
                } else {
                    console.error(`Failed to delete rendezvous with ID ${selectedRendezvousId}`);
                }
            };
        }
    });

// Event listener to deselect rendezvous when clicking on anything other than a rendezvous
    document.addEventListener('click', function (event) {
        let isInsideTable = event.target.closest('tr');
        console.log('Rendez-vous sélectionné ID : ' + selectedRendezvousId);
        if (!isInsideTable && selectedRendezvous) {
            selectedRendezvous.style.backgroundColor = '';
            selectedRendezvous.classList.remove("selected");
            deleteButton.style.display = 'none';
            selectedRendezvous = null;
            selectedRendezvousId = null;
        }
    });

});

