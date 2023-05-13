/* GESTION FORMULAIRE
*   -   initialize
*   -   RemplirInputsFormulaire
*   -   clearInputsFormulaire
 */
class Formulaire {
    // Assigner sa place dans la vue
    constructor(formId) {
        this.formId = formId;
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
                url: '../controllers/crud_rendezvous.php',
                data: formData,
                success: function (data) {
                    // check console du navigateur
                    console.log(data);
                },
                error: function () {
                    console.error('There was a problem with the request');
                }
            });
        });
        // Event Listener Bouton sauver qui a 2 utilités
        $('#saveUpdateBtn').on('click', function (event) {
            event.preventDefault();
            // valeur update (modifier) ou save (créer)
            let action = idInput.val() ? 'update' : 'save';
            typeRequest.val(action);
            formulaire.trigger('submit');
            // reload changements
            listeRendezvous.reloadRendezvousList();
        });
        // Event Listener Bouton delete
        $('#deleteBtn').on('click', function (event) {
            event.preventDefault();
            typeRequest.val('delete');
            formulaire.trigger('submit');
            listeRendezvous.reloadRendezvousList();

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
    }

    // Methode utilitaire pour vider les inputs
    clearInputsFormulaire() {
        const form = document.getElementById(this.formId);
        // l'input id est hidden dans la vue
        const inputs = form.querySelectorAll("input[type='id'],input[type='text'], input[type='date'], input[type='time'], textarea");
        inputs.forEach((input) => {
            // for each vider
            input.value = "";
        });
    }
}
