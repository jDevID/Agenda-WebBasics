/* GESTION CALENDRIER
*   -   assignerDependances
*   -   initCalendrier
*   -   buildCalendrierHTML
*   -   cycleMoisAnnee
*   -   selectionJourCalendrier
*   -   clickVideClearInputs
*
 */
class Calendrier {
    constructor(containerId, listeRendezvous, formulaire) {
        this.calendrierId = containerId;
        this.formulaire = formulaire;
        this.annee = new Date().getFullYear();
        this.mois = new Date().getMonth();
    }

    // Assigne la liste de RDV et le formulaire au calendrier
    assignerDependances(listeRendezvous, formulaire) {
        this.formulaire = formulaire;
    }

    // Assigne une couleur au jour sélectionné
    // et insère les données correspondantes aux inputs
    selectionJourCalendrier() {
        const days = document.querySelectorAll(".day");
        days.forEach((day) => {
            day.addEventListener("click", () => {
                // un seul jour sélectionné à la fois
                this.clearSelectedDays();
                day.classList.add("selected-day");
                // on parse la date
                const date = new Date(parseInt(day.dataset.year), parseInt(day.dataset.month), parseInt(day.dataset.day));
                // on envoie aux inputs du formulaire
                this.formulaire.RemplirInputsFormulaire(date, {
                    id: "",
                    name: "",
                    description: `Rendez-vous du ${day.dataset.day} du ${date.getMonth() + 1} ${date.getFullYear()}\n`,
                    start_hour: "08:00",
                    end_hour: "09:00"
                });
            });
        });
    }

    // clear les inputs du formulaireID si on click en dehors de tout component tangible
    clickVideClearInputs() {
        document.body.addEventListener("click", (event) => {
            // en cas de click en dehors des containers suivant :
            if (!event.target.closest("#calendar") && !event.target.closest("#rendezvousFormElem") && !event.target.closest("#rendezvousList") && !event.target.closest("#prevMonth") && !event.target.closest("#nextMonth")) {
                // on vide les inputs du formulaire d'édition de RDV
                this.formulaire.clearInputsFormulaire();
            }
        });
    }

    // Gestion de la navigation du calendrier
    cycleMoisAnnee() {
        const moisPrecedent = document.getElementById("btn_moisPrecedentId");
        const moisSuivant = document.getElementById("btn_moisSuivantId");
        // Assigné aux boutons correspondants
        moisPrecedent.addEventListener("click", () => {
            this.mois--;
            if (this.mois < 0) {
                this.mois = 11;
                this.annee--;
            }
            // rebuild
            this.initCalendrier();
        });
        moisSuivant.addEventListener("click", () => {
            this.mois++;
            if (this.mois > 11) {
                this.mois = 0;
                this.annee++;
            }
            this.initCalendrier();
        });
    }

    // Initialisation du calendrier et set des Actions ensuite
    initCalendrier() {
        this.calendrierHTML = this.buildCalendrierHTML(this.annee, this.mois);
        document.getElementById(this.calendrierId).innerHTML = this.calendrierHTML;
        this.selectionJourCalendrier();
        this.clickVideClearInputs();
    }

    // On génère les balises HTML du calendrier
    buildCalendrierHTML(year, month) {
        const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

        // Initialisation des conteneurs HTML
        let calendarHTML = '<div class="calendar-calendrierId">';
        calendarHTML += `<div class="month-container" data-month="${month}">`;

        // Titre du mois + année, création de la table HTML
        calendarHTML += `<h2>${year} ~ ${months[month]}</h2>`;
        calendarHTML += '<table>';
        calendarHTML += '<thead><tr><th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th><th>Dim</th></tr></thead>';
        calendarHTML += '<tbody>';

        // Calcul des premiers et derniers jours à l'aide de la librairie built-in
        let firstDay = new Date(year, month, 1).getDay();
        let daysInMonth = new Date(year, month + 1, 0).getDate();

        // On décale firstDay pour que Lundi soit le premier jour
        firstDay = (firstDay + 6) % 7;

        // Boucle des semaines
        let day = 1;
        let rowCount = Math.ceil((firstDay + daysInMonth) / 7);
        for (let i = 0; i < rowCount; i++) {
            calendarHTML += '<tr>';
            // Boucle des cellules (jours)
            for (let j = 0; j < 7; j++) {
                // Si le mois n'a pas commencé ou est fini : cellule vide
                if ((i === 0 && j < firstDay) || day > daysInMonth) {
                    calendarHTML += '<td></td>';
                } else {
                    // sinon on crée la structure on assignant les valeurs et le formattage de la date
                    calendarHTML += `<td class="day" data-day="${day}" data-year="${year}" data-month="${month}" data-date="${day.toString().padStart(2, '0')}-${(month + 1).toString().padStart(2, '0')}-${year}">${day}</td>`;
                    day++;
                }
            }
            calendarHTML += '</tr>';
        }
        // Fermetures des balises
        calendarHTML += '</tbody>';
        calendarHTML += '</table>';
        calendarHTML += '</div>';
        calendarHTML += '</div>';

        return calendarHTML;
    }

    // méthode utilitaire pour clear la sélection calendrier
    clearSelectedDays() {
        const selectedDays = document.querySelectorAll(".selected-day");
        selectedDays.forEach((selectedDay) => {
            selectedDay.classList.remove("selected-day");
        });
    }
}
