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
    getRendezvousForClient(clientId) {
        const self = this; // résoudre le this en callback
        fetch(`../../agendapp/controllers/client_crud.php?action=getRendezvousForClient&id=${clientId}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.status === 'success') {
                    self.updateRendezvousListe(data.data);
                    console.log(data.data);
                    return data.data;
                } else {
                    console.error(data.message);
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });

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
            url: '../../agendapp/controllers/rendezvous_crud.php',
            data: {action: 'list'},
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    console.log(response);
                    if (Array.isArray(response.data)) {
                        self.rendezvousData = response.data;
                        self.creerListeElementHTML(response.data);
                    } else {
                        console.error('Error: rendezvousData is not an array');
                    }
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

    updateRendezvousListe(rendezvousData) {
        if (Array.isArray(rendezvousData)) {
            this.rendezvousData = rendezvousData;
            this.creerListeElementHTML(rendezvousData);
        } else {
            console.error('Error: rendezvousData is not an array');
        }
    }

}