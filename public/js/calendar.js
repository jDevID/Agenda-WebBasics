// Créer le calendrier selon une année spécifique
function createCalendar(year) {
    const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    // Initialisation des balises HTML
    let calendarHTML = '<div class="calendar-container">';
    // Boucle des mois
    for (let month = 0; month < 12; month++) {
        // Container global calendrier et titre
        calendarHTML += `<div class="month-container" data-month="${month}">`;
        calendarHTML += `<h2>${months[month]}</h2>`;

        // tableau dans le mois
        calendarHTML += '<table>';
        calendarHTML += '<thead><tr><th>Dim</th><th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th></tr></thead>';
        calendarHTML += '<tbody>';

        // Calcul des premiers et derniers jours à l'aide de la librairie built-in
        let firstDay = new Date(year, month, 1).getDay();
        let daysInMonth = new Date(year, month + 1, 0).getDate();

        // Boucle des jours
        let day = 1;
        for (let i = 0; i < 6; i++) {
            calendarHTML += '<tr>';
            for (let j = 0; j < 7; j++) {
                if ((i === 0 && j < firstDay) || day > daysInMonth) {
                    calendarHTML += '<td></td>';
                } else {
                    calendarHTML += `<td data-day="${day}">${day}</td>`;
                    day++;
                }
            }
            calendarHTML += '</tr>';
        }

        // Fermer les balises HTML
        calendarHTML += '</tbody>';
        calendarHTML += '</table>';
        calendarHTML += '</div>';
    }
    calendarHTML += '</div>';
    // Ajout du code à l'élément d'id #calendar
    document.getElementById("calendar").innerHTML = calendarHTML;

}
// TODO: features (cycling months, buttons, cells for appointments, ajax to asynchronous...)
