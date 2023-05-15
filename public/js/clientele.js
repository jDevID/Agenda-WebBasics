class Clientele {
    constructor(listeClientId, listeRendezvousId) {
        this.listeClient = document.getElementById(listeClientId);
        this.listeRendezvous = document.getElementById(listeRendezvousId);

        this.selectedClientId = null;
        this.addEventListeners();
    }

    addEventListeners() {
        $('#add_client_form').on('submit', (event) => {
            console.log('#add_client_form -> submit')
            event.preventDefault();
            this.saveChanges();
        });

        $('#delete_client_form').on('submit', (event) => {

            event.preventDefault();
            this.selectedClientId = $('#client_id').val();
            console.log('#delete_client_form -> submit')
            this.deleteClient();
        });
        $('#btn_calendrier').on('click', function (event) {
            window.location.href = "../../agendapp/views/main_view.php";
        });
        $(document).on('click', '#client_list li', function (event) {
            this.selectedClientId = $(event.currentTarget).data('id');
            console.log('#client_list -> client selec ID = ', this.selectedClientId); // check id

            $('#client_id').val(this.selectedClientId);
            this.refreshClientRendezvous();
        }.bind(this));
    }

    refreshClientRendezvous() {
        console.log('getRendezvousForClient  -> ID = ', this.selectedClientId); // Check l'id
        $.ajax({
            type: 'GET',
            url: '../../agendapp/controllers/client_crud.php',
            data: {action: 'getRendezvousForClient', id: this.selectedClientId},
            dataType: 'json',
            success: (response) => {
                if (response.status === 'success') {
                    console.log('Rdv futur id '+this.selectedClientId+' = '+response.data);
                    this.createRendezvousElementHTML(response.data);
                } else {
                    console.error('Error:', response.message);
                }
            },
            error: this.handleAjaxError
        });
    }

    createRendezvousElementHTML(data) {
        this.listeRendezvous.innerHTML = '';
        for (let item of data) {
            let li = document.createElement('li');
            li.textContent = item.date + ' - ' + item.start_hour;
            this.listeRendezvous.appendChild(li);
        }
    }

    refreshClientList() {
        $.ajax({
            type: 'GET',
            url: '../../agendapp/controllers/client_crud.php?action=list',
            dataType: 'json',
            success: (response) => {
                if (response.status === 'success') {
                    this.creerListeElementHTML(response.data);
                } else {
                    console.error('Error:', response.message);
                }
            },
            error: this.handleAjaxError
        });
    }

    creerListeElementHTML(data) {
        this.listeClient.innerHTML = '';
        for (let item of data) {
            let li = document.createElement('li');
            li.textContent = item.id + ' :' + item.name + ' - ' + item.email;
            li.dataset.id = item.id;
            // convertir l'id en string
            // li.dataset.id = item.id.toString();
            this.listeClient.appendChild(li);
        }
    }

    saveChanges() {
        let name = $('#client_name').val();
        let email = $('#client_email').val();

        $.ajax({
            type: 'POST',
            url: '../../agendapp/controllers/client_crud.php',
            data: {action: 'save', name: name, email: email},
            success: (response) => {
                if (response.status === 'success') {
                    this.refreshClientList();
                } else {
                    console.error('Error:', response.message);
                }
            },
            error: this.handleAjaxError
        });
    }

    deleteClient() {
        $.ajax({
            type: 'POST',
            url: '../../agendapp/controllers/client_crud.php',
            data: {action: 'delete', id: this.selectedClientId},
            dataType: 'json',
            success: (response) => {
                if (response.status === 'success') {
                    this.refreshClientList();
                } else {
                    console.error('Error:', response.message);
                }
            },
            error: this.handleAjaxError
        });
    }

    handleAjaxError(jqXHR, textStatus, errorThrown) {
        console.error('Error:', textStatus, errorThrown);
        console.log(jqXHR.responseText);
    }
}

