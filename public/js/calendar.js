// TODO: features (cells pour organiser rdv et congés, style css, bootstrap,...)

// Créer le calendrier selon une année spécifique
function createCalendar(year, month) {
    const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    // Initialisation des contqiners HTML
    let calendarHTML = '<div class="calendar-container">';
    calendarHTML += `<div class="month-container" data-month="${month}">`;

    // Titre du mois + année, création de la table HTML
    calendarHTML += `<h2>${year} ~ ${months[month]}</h2>`;
    calendarHTML += '<table>';
    calendarHTML += '<thead><tr><th>Dim</th><th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th></tr></thead>';
    calendarHTML += '<tbody>';

    // Calcul des premiers et derniers jours à l'aide de la librairie built-in
    let firstDay = new Date(year, month, 1).getDay();
    let daysInMonth = new Date(year, month + 1, 0).getDate();

    // Boucle des semaines
    let day = 1;
    for (let i = 0; i < 6; i++) {
        calendarHTML += '<tr>';
        // Boucle des cellules (jours)
        for (let j = 0; j < 7; j++) {
            // Si le jour est en dehors du mois: cellule vide
            if ((i === 0 && j < firstDay) || day > daysInMonth) {
                calendarHTML += '<td></td>';
            } else {
                // Cellule: numéro du jour stocké dans data-day !
                calendarHTML += `<td data-day="${day}">${day}</td>`;
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
    // Ajout du code HTML à l'id #calendar
    document.getElementById("calendar").innerHTML = calendarHTML;
}
// Une fois que le document est prêt, gestion du cycle des mois
$(document).ready(function () {
    let year = 2023;
    let month = 4;
    createCalendar(year, month);

    for (let month = 0; month < 12; month++) {
        updateCalendar(year, month);
    }

    // Gestionnaire d'événement pour le bouton "Mois précédent"
    $(document).on('click', '#prevMonth', function () {
        // Conditions pour passer au mois précédent
        if (year > 2023 || (year === 2023 && month > 0)) {
            if (month === 0) {
                month = 11;
                year--;
            } else {
                month--;
            }
            // Call avec le mois précédent
            createCalendar(year, month);
        }
    });

    // Gestionnaire d'événement pour bouton du mois suivant
    $(document).on('click', '#nextMonth', function () {
        // Conditions pour passer au mois suivant
        if (month < 11 || year < 9999) {
            if (month === 11) {
                month = 0;
                year++;
            } else {
                month++;
            }
            // Call avec le mois prochain
            createCalendar(year, month);
        }
    });

});


function updateCalendar(year, month) {
// TODO: handling appointment/vacations days retrieval with ajax
}

