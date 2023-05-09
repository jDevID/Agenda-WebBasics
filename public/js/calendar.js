class Calendar {
    constructor(containerId, formId, rendezvousListId) {
        this.containerId = containerId;
        this.formId = formId;
        this.rendezvousListId = rendezvousListId;
        this.year = new Date().getFullYear();
        this.month = new Date().getMonth();
        this.createCalendar();
        this.addEventListeners();
    }


    // Responsable des call Ajax afin de get la liste de rdv's du serveur.
    // Si la request est successful on envoit le data à la méthode updateRendezvousList
    reloadRendezvousList() {
        let self = this;
        $.ajax({
            type: 'GET',
            url: '../controllers/getList_rendezvous.php',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    self.updateRendezvousList(response.data);
                } else {
                    console.error('Il y a eu un problème lors du chargement de la liste des rendez-vous:', response.message);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Il y a eu un problème lors du chargement de la liste des rendez-vous:', textStatus, errorThrown);
                console.log(jqXHR.responseText);
            }
        });
    }
    // Update la DOM avec la list des RDV, trie puis crée la list d'élément
    // L'event listener permet de remplir les inputs du formulaire en 1 click
    updateRendezvousList(rendezvousData) {
        console.log(rendezvousData);
        const rendezvousList = document.getElementById(this.rendezvousListId);
        rendezvousList.innerHTML = "";
        rendezvousData.sort((a, b) => {
            if (a.date === b.date) {
                if (a.start_hour === b.start_hour) {
                    return a.name.localeCompare(b.name);
                }
                return a.start_hour.localeCompare(b.start_hour);
            }
            return a.date.localeCompare(b.date);
        });
        rendezvousData.forEach((item) => {
            const startHourParts = item.start_hour.split(':');
            const endHourParts = item.end_hour.split(':');
            item.start_hour = startHourParts.length === 2 ? `${item.start_hour}:00` : item.start_hour;
            item.end_hour = endHourParts.length === 2 ? `${item.end_hour}:00` : item.end_hour;
            const date = new Date(item.date);
            const day = date.getDate();
            const monthNames = [
                "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
                "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
            ];
            const monthName = monthNames[date.getMonth()];

            const listItem = document.createElement("li");
            listItem.textContent = `${item.name} - ${item.date} - ${item.start_hour} - ${item.end_hour}`; // Use 'item' instead of 'rendezvous'
            listItem.dataset.rendezvous = JSON.stringify(item);
            listItem.addEventListener("click", () => {
                this.removeSelectedDay();
                this.fillFormFields(new Date(item.date), item);
                document.getElementById("action").value = "update";
                document.getElementById("id").value = item.id;
            });

            rendezvousList.appendChild(listItem);
        });
    }

// On ajoute les event listener
    addEventListeners() {
        const prevButton = document.getElementById("prevMonth");
        const nextButton = document.getElementById("nextMonth");

        prevButton.addEventListener("click", () => {
            this.month--;
            if (this.month < 0) {
                this.month = 11;
                this.year--;
            }
            this.createCalendar();
        });

        nextButton.addEventListener("click", () => {
            this.month++;
            if (this.month > 11) {
                this.month = 0;
                this.year++;
            }
            this.createCalendar();
        });

        this.setDocumentClickEventListener();
    }


// On remplit les champs avec la date sélectionnée
    fillFormFields(date, rendezvousData = null) {
        this.clearFormInputs();
        // format the date
        let adjustedDate = new Date(date.getTime() - (date.getTimezoneOffset() * 60 * 1000));

        const idElem = document.getElementById("id");
        const dateElem = document.getElementById("date");
        const startHourElem = document.getElementById("start_hour");
        const endHourElem = document.getElementById("end_hour");
        const nameElem = document.getElementById("name");
        const descriptionElem = document.getElementById("description");
        const selectedDayElem = document.getElementById("selected_day");

        if (idElem) {
            idElem.value = rendezvousData ? rendezvousData.id : "";
        }

        if (dateElem) {
            dateElem.value = adjustedDate.toISOString().split("T")[0];
        }

        if (startHourElem) {
            startHourElem.value = rendezvousData ? rendezvousData.start_hour.split(':')[0] + ':' + rendezvousData.start_hour.split(':')[1] : "08:00";
        }
        if (endHourElem) {
            endHourElem.value = rendezvousData ? rendezvousData.end_hour.split(':')[0] + ':' + rendezvousData.end_hour.split(':')[1] : "08:30";
        }

        if (nameElem) {
            nameElem.value = rendezvousData ? rendezvousData.name : "";
        }
        if (descriptionElem) {
            descriptionElem.value = rendezvousData ? rendezvousData.description : "";
        }

        const monthNames = [
            "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
            "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
        ];
        const day = adjustedDate.getDate();
        const monthName = monthNames[adjustedDate.getMonth()];
        const year = adjustedDate.getFullYear();

        // Fill with the formatted date
        if (selectedDayElem) {
            selectedDayElem.innerHTML = day + " " + monthName + " " + year;
        }

    }


    // click sur cellule du jour rempli le formulaire
    setDayClickEventListeners() {
        const days = document.querySelectorAll(".day");
        const self = this;

        days.forEach((day) => {
            day.addEventListener("click", function () {
                self.removeSelectedDay();
                this.classList.add("selected-day");
                const date = new Date(this.dataset.year, this.dataset.month, this.dataset.day);

                const monthNames = [
                    "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
                    "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
                ];
                const monthName = monthNames[date.getMonth()];
                const year = date.getFullYear();
                const defaultDescription = `Rendez-vous du ${day.dataset.day} ${monthName} ${year}\n`;

                // défaut
                self.fillFormFields(date, {
                    name: "",
                    description: defaultDescription,
                    start_hour: "08:00",
                    end_hour: "09:00"
                });
            });
        });
    }

    // clear les inputs du formulaire si on click en dehors de tout component tangible
    setBodyClickEventListener() {
        const self = this;
        document.body.addEventListener("click", (event) => {
            if (!event.target.closest("#calendar") && !event.target.closest("#rendezvousFormElem") && !event.target.closest("#rendezvousList") && !event.target.closest("#prevMonth") && !event.target.closest("#nextMonth")) {
                // Reset all form fields
                document.getElementById("name").value = "";
                document.getElementById("description").value = "";
                document.getElementById("start_hour").value = "";
                document.getElementById("end_hour").value = "";
                document.getElementById("date").value = "";
            }
        });
    }

    setDocumentClickEventListener() {
        const self = this;
        document.addEventListener("click", function (event) {
            if (event.target.matches(".rendezvous")) {
                const rendezvousData = {
                    id: event.target.dataset.id,
                    name: event.target.innerText,
                    date: event.target.parentNode.dataset.date,
                    start_hour: "08:00",
                    end_hour: "08:30",
                    description: ""
                };
                const date = new Date(rendezvousData.date);
                self.fillFormFields(date, rendezvousData);
                self.removeSelectedDay();
                event.target.parentNode.classList.add("selected-day");
            }
        });

    }

    // On crée le calendrier
    createCalendar() {
        this.calendarHTML = this.generateCalendarHTML(this.year, this.month);
        document.getElementById(this.containerId).innerHTML = this.calendarHTML;
        this.setDayClickEventListeners();
    }

    // On génère le HTML du calendrier
    generateCalendarHTML(year, month) {
        const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

        // Initialisation des conteneurs HTML
        let calendarHTML = '<div class="calendar-container">';
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
                    // Cellule : numéro du jour stocké dans data-day et data-date !
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


    // On enlève le jour sélectionné
    removeSelectedDay() {
        const prevSelected = document.querySelector(".selected-day");
        if (prevSelected) {
            prevSelected.classList.remove("selected-day");
        }
    }

    // vider les champs du formulaire
    clearFormInputs() {
        const form = document.getElementById(this.formId);
        const inputs = form.querySelectorAll("input[type='text'], input[type='date'], input[type='time'], textarea");
        inputs.forEach((input) => {
            input.value = "";
        });
    }
}

let calendar; // Declare the variable at the global scope
document.addEventListener("DOMContentLoaded", function () {
    calendar = new Calendar("calendar", "rendezvousFormElem", "rendezvousList");
    calendar.reloadRendezvousList();
    calendar.setBodyClickEventListener();
});