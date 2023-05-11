/* GESTION LISTE DE RENDEZVOUS
*   -   assignerDependances
*   -   RemplirInputsFormulaire
*   -   clearInputsFormulaire
*   -   creerListeElementHTML
*   -   refreshRendezvousListe
 */
class ListeRendezvous {
    constructor(listRendezvousId) {
        this.listRendezvousId = listRendezvousId;
        this.rendezvousData = [];
    }

    // elements de la liste sont clickable
    initialize() {
        const self = this;  // pour résoudre this.formulaire is undefined
        $('#' + this.listRendezvousId).on('click', 'li', function () {
            const rendezvousData = $(this).data('rendezvous');
            // on remplit les Inputs
            self.formulaire.RemplirInputsFormulaire(new Date(rendezvousData.date), rendezvousData);
            $('#action').val('update');
            $('#id').val(rendezvousData.id);
        });
    }

    // Assigne le calendrier et le formulaire
    assignerDependances(calendar, formulaire) {
        this.calendar = calendar;
        this.formulaire = formulaire;
    }

    // Update la DOM avec la list des RDV, trie puis crée la list d'élément
    // L'event listener permet de remplir les inputs du formulaireID en 1 click
    creerListeElementHTML(rendezvousData) {
        const rendezvousList = document.getElementById(this.listRendezvousId);
        rendezvousList.innerHTML = "";
        rendezvousData.sort((a, b) => a.date === b.date ? a.start_hour.localeCompare(b.start_hour) : a.date.localeCompare(b.date));
        rendezvousData.forEach((item) => {
            item.start_hour = item.start_hour.length === 5 ? `${item.start_hour}:00` : item.start_hour;
            item.end_hour = item.end_hour.length === 5 ? `${item.end_hour}:00` : item.end_hour;
            const listItem = document.createElement("li");
            listItem.textContent = `${item.name} - ${item.date} - ${item.start_hour} - ${item.end_hour}`;
            listItem.dataset.rendezvous = JSON.stringify(item);
            listItem.addEventListener("click", () => {
                this.calendar.clearSelectedDays();
                this.formulaire.RemplirInputsFormulaire(new Date(item.date), item);
                document.getElementById("action").value = "update";
                document.getElementById("id").value = item.id;
            });
            rendezvousList.appendChild(listItem);
            this.rendezvousData = rendezvousData;
        });
         // sauvegarde des data dans l'array
    }

    // Responsable des call Ajax afin de get la liste de rdv's du serveur.
    // Si la request est successful on envoit le data à la méthode creerListeElementHTML
    refreshRendezvousListe() {
        let self = this;
        $.ajax({
            type: 'GET',
            url: '../controllers/crud_rendezvous.php',
            data: {action: 'list'},
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                     console.log(response)
                     self.rendezvousData = response.data;
                     self.creerListeElementHTML(response.data);
                } else {
                    console.error('Error:', response.message);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
                console.log(jqXHR.responseText);
            }
        });
    }


}