$(document).ready(function () {
    fetchRendezvousList();
});

function fetchRendezvousList() {
    $.get('/ProjetWeb/controllers/getRendezvousList.php', function (data) {
        let rendezvousList = $('#rendezvousList');
        rendezvousList.empty();

        data.forEach(function (item) {
            let date = new Date(item.date);
            let day = date.getDate();
            let monthNames = [
                "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
                "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
            ];
            let monthName = monthNames[date.getMonth()];

            let listItem = $('<li></li>').text(`${day}-${monthName} ${item['start_hour']} ${item['name']}`);

            listItem.on('click', function () {
                // Set form fields with the clicked rendezvous data
                $('#name').val(item.name);
                $('#description').val(item.description);
                $('#date').val(item['date']);
                $('#start_hour').val(item['start_hour']);
                $('#end_hour').val(item['end_hour']);

                // Set the selected day in the calendar
                calendar.removeSelectedDay();
                calendar.fillFormFields(new Date(item.date));
            });
            rendezvousList.append(listItem);
        });
    });
}
