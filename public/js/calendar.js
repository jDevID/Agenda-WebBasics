class Calendar {
    constructor(containerId, formId) {
        this.containerId = containerId;
        this.formId = formId;
        this.year = new Date().getFullYear();
        this.month = new Date().getMonth();
        this.createCalendar();
        this.addEventListeners();
    }

    // On crée le calendrier, trop cool !
    createCalendar() {
        this.calendarHTML = this.generateCalendarHTML(this.year, this.month);
        document.getElementById(this.containerId).innerHTML = this.calendarHTML;
        this.setDayClickEventListeners();
    }

    // On recharge la liste des rendez-vous, trop stylé !
    reloadRendezvousList() {
        const self = this;
        $.ajax({
            type: 'GET',
            url: '../../controllers/getList_rendezvous.php',
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
            }
        });
    }

    // On génère le HTML du calendrier, trop fort !
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

// vider les champs du formulaire
    clearFormInputs() {
        const form = document.getElementById(this.formId);
        const inputs = form.querySelectorAll("input[type='text'], input[type='date'], input[type='time'], textarea");
        inputs.forEach((input) => {
            input.value = "";
        });
    }

// On remplit les champs avec la date sélectionnée
    fillFormFields(date, rendezvousData = null) {
        this.clearFormInputs();
        // formater la date
        let adjustedDate = new Date(date.getTime() + (date.getTimezoneOffset() * 60 * 1000));
        document.getElementById("date").value = adjustedDate.toISOString().split("T")[0];

        // heures de début et de fin
        document.getElementById("start_hour").value = rendezvousData ? rendezvousData.start_hour : "08:00";
        document.getElementById("end_hour").value = rendezvousData ? rendezvousData.end_hour : "08:30";

        const monthNames = [
            "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
            "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
        ];
        const day = adjustedDate.getDate();
        const monthName = monthNames[adjustedDate.getMonth()];
        const year = adjustedDate.getFullYear();

        // On remplit avec la date formatée
        document.getElementById("description").value = rendezvousData ? `${rendezvousData.description} - ${day} ${monthName} ${year}` : `${day} ${monthName} ${year} -`;

        // On remplit le champ nom si rendezvousData est fourni
        if (rendezvousData) {
            document.getElementById("name").value = rendezvousData.name;
        }
    }

// On enlève le jour sélectionné
    removeSelectedDay() {
        const prevSelected = document.querySelector(".selected-day");
        if (prevSelected) {
            prevSelected.classList.remove("selected-day");
        }
    }

    // event listener pour les jours
    setDayClickEventListeners() {
        const days = document.querySelectorAll(".day");
        const self = this;

        days.forEach((day) => {
            day.addEventListener("click", function () {
                self.removeSelectedDay();
                this.classList.add("selected-day");
                const date = new Date(this.dataset.year, this.dataset.month, this.dataset.day);
                self.fillFormFields(date);
            });
        });
    }

    // refresh rendezvous
    updateRendezvousList(rendezvousData) {
        const days = document.querySelectorAll(".day");
        days.forEach((day) => {
            const dayData = day.dataset.date;
            const rendezvousOnDay = rendezvousData.filter((item) => item.date === dayData);
            day.innerHTML = `<span>${day.dataset.day}</span>`;
            if (rendezvousOnDay.length > 0) {
                day.classList.add("has-rendezvous");
                rendezvousOnDay.forEach((item) => {
                    day.innerHTML += `<div class="rendezvous" data-id="${item.id}">${item.name}</div>`;
                });
            } else {
                day.classList.remove("has-rendezvous");
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
        function showError(message) {
            alert(`Error: ${message}`);
        }
    }
}

const calendar = new Calendar("calendar", "event-form");
calendar.reloadRendezvousList();