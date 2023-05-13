class Clientele {
    constructor(listId) {
        this.clientList = document.getElementById(listId);
        this.selectedClientId = null;
        this.addEventListeners();
    }

    addEventListeners() {
        $('#add_client_form').on('submit', (event) => {
            event.preventDefault();
            this.saveChanges();
        });

        $('#delete_client_form').on('submit', (event) => {
            event.preventDefault();
            this.selectedClientId = $('#client_id').val();
            this.deleteClient();
        });

        $(document).on('click', '#client_list li', function (event) {
            this.selectedClientId = $(event.currentTarget).data('id');
            $('#client_id').val(this.selectedClientId);
        }.bind(this));
    }

    refreshClientList() {
        $.ajax({
            type: 'GET',
            url: '../controllers/crud_client.php?action=list',
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
        this.clientList.innerHTML = '';
        for (let item of data) {
            let li = document.createElement('li');
            li.textContent = item.name + ' - ' + item.email;
            li.dataset.id = item.id;
            this.clientList.appendChild(li);
        }
    }

    saveChanges() {
        let name = $('#client_name').val();
        let email = $('#client_email').val();

        $.ajax({
            type: 'POST',
            url: '../controllers/crud_client.php',
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
            url: '../controllers/crud_client.php',
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

