/* GESTION FORMULAIRE
*   -   initialize
*   -   RemplirInputsFormulaire
*   -   clearInputsFormulaire
 */
class Formulaire {
    // Assigner sa place dans la vue
    constructor(formId, listeRendezvous) {
        this.formId = formId;
        this.listeRendezvous = listeRendezvous;
        this.isConge = false;
    }

    initialize() {
        // Déclarations des valeurs utiles
        let self = this;
        let formulaire = $('#' + this.formId);
        let typeRequest = $('#action');
        let idInput = $('#id');

        formulaire.on('submit', function (event) {
            // Empêcher form.submit() classique
            event.preventDefault();
            // recuperation des données
            let formData = $(this).serialize();

            // Requête AJAX au serveur
            $.ajax({
                // Coordonnées
                type: 'POST',
                url: '../controllers/rendezvous_crud.php',
                data: formData,
                success: function (data) {
                    // Ajax étant asynchrone c'est à la suite qu'on refresh
                    listeRendezvous.refreshRendezvousListe();
                    calendar.initCalendrier();

                },
                error: function () {
                    console.error('There was a problem with the request');
                }
            });
        });
        // Event Listener Bouton sauver qui a 2 utilités
        $('#saveUpdateBtn').off('click').on('click', function (event) {
            event.preventDefault();
            // valeur update (modifier) ou save (créer)
            let action = idInput.val() ? 'update' : 'save';
            typeRequest.val(action);
            formulaire.trigger('submit');
        });
        // Event Listener Bouton delete
        $('#deleteBtn').off('click').on('click', function (event) {
            event.preventDefault();
            typeRequest.val('delete');
            formulaire.trigger('submit');
        });
        this.populateClientSelectBox();
        // Event Listener Bouton conge
        $('#congeBtn').off('click').on('click', function (event) {
            event.preventDefault();
            let dateInput = $('#date');
            self.toggleConge(dateInput.val());
            console.log(dateInput.val());
        });

        let client = $('#client');
        client.change(() => {
            let clientId = client.val();
            console.log(clientId);
            console.log(client.val());
            let rdvOfCLient = this.listeRendezvous.getRendezvousForClient(clientId);
            console.log()
        });
    }

    populateClientSelectBox() {
        let self = this;
        $.ajax({
            type: 'GET',
            url: '../controllers/client_crud.php',
            data: {action: 'list'},
            dataType: 'json',
            success: function (response) {
                let select = $('#client');
                for (let client of response.data) {
                    select.append(new Option(client.id + ', ' + client.name, client.id));
                }

                // remplir le nom
                select.off('change').change(function () {
                    let selectedOption = $(this).find('option:selected').text();
                    let clientName = selectedOption.split(', ')[1]; // pour avoir le nom correct
                    $('#name').val(clientName);
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
                console.log(jqXHR.responseText);
            }
        });
    }

    toggleConge(date) {
        if (!date) {
            console.error('Date is undefined or null');
            return;
        }

        let self = this; //


        // Avoir congé ou non pour la date
        $.ajax({
            type: 'GET',
            url: '../../agendapp/controllers/conge_crud.php',
            data: {action: 'check', date: date},
            dataType: 'json',
            async: false, // AJAX synchrone
            success: function (response) {
                console.log(response);
                self.isConge = response.data.isConge;
            },
            error: function () {
                console.error('There was a problem with the request');
            }
        });

        this.isConge = !this.isConge;
        // AJAX pour la sauvegarde et l'update
        $.ajax({
            type: 'POST',
            url: '../../agendapp/controllers/conge_crud.php',
            data: {action: this.isConge ? 'save' : 'delete', date: date},
            success: function (data) {
                // reload calendrier
                calendar.initCalendrier();
            },
            error: function () {
                console.error('There was a problem with the request');
            }
        });
    }

    // Remplir les inputs de données par défaut selon le jour sélectionné
    RemplirInputsFormulaire(date, rendezvousData = null) {
        this.clearInputsFormulaire();
        // Calcul de la date correct
        let adjustedDate = new Date(date.getTime() - (date.getTimezoneOffset() * 60 * 1000));
        // Assignation des input du form
        const formInputNom = document.getElementById("name");
        const formInputId = document.getElementById("id");
        const formInputDate = document.getElementById("date");
        const formInputHeureDebut = document.getElementById("start_hour");
        const formInputHeureFin = document.getElementById("end_hour");
        const formInputDescription = document.getElementById("description");
        const formInputSelectionJour = document.getElementById("selected_day");
        const formInputClient = document.getElementById("client");

        // Insertion donnée
        if (formInputId) {
            formInputId.value = rendezvousData ? rendezvousData.id : "";
        }
        if (formInputDate) {
            formInputDate.value = adjustedDate.toISOString().split("T")[0];
        }
        if (formInputHeureDebut) {
            formInputHeureDebut.value = rendezvousData ? rendezvousData.start_hour.split(':')[0] + ':' + rendezvousData.start_hour.split(':')[1] : "08:00";
        }
        if (formInputHeureFin) {
            formInputHeureFin.value = rendezvousData ? rendezvousData.end_hour.split(':')[0] + ':' + rendezvousData.end_hour.split(':')[1] : "08:30";
        }
        if (formInputNom) {
            formInputNom.value = rendezvousData ? rendezvousData.name : "";
        }
        if (formInputDescription) {
            formInputDescription.value = rendezvousData ? rendezvousData.description : "";
        }
        if (formInputClient) {
            let clientId = rendezvousData ? rendezvousData.client_id : "";
            //check si la valeur existe
            if ($('#client option[value="' + clientId + '"]').length > 0) {
                // si ça existe on select la valeur
                formInputClient.value = clientId;
            } else {
                // si ça n'exite pas on clear
                formInputClient.value = "";
            }
        }
    }

    // Methode utilitaire pour vider les inputs
    clearInputsFormulaire() {
        const form = document.getElementById(this.formId);
        // l'input id est hidden dans la vue
        const inputs = form.querySelectorAll("input[type='id'],input[type='text'], input[type='date'], input[type='time'], textarea, select");
        inputs.forEach((input) => {
            // vider pour chacun
            input.value = "";
        });
    }

}

