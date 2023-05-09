<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgendApp | Rendez-vous</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/calendar.css">
    <!-- JQuery librairie Js, permet la manipulation aisée de documents HTML -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<!-- Contrôles du calendrier -->
<body>
<div id="calendarContainer" class="calendar-container">
    <div id="calendar"></div>
    <div class="calendar-controls">
        <button id="prevMonth">Précédent</button>
        <button id="nextMonth">Suivant</button>
    </div>
</div>


<div class="content">
    <div class="left-column">
        <div id="rendezvousForm" class="rendezvous-form">
            <h2>Edition Rendez-vous</h2>
            <form id="rendezvousFormElem" action="../controllers/save_rendezvous.php" method="post">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="action" id="action" value="save">
                <label for="name">Nom:</label>
                <input type="text" name="name" id="name" required minlength="3" maxlength="35">
                <br>
                <label for="description">Description:</label>
                <textarea name="description" id="description" required minlength="20" maxlength="300"></textarea>
                <br>
                <label for="start_hour">De:</label>
                <input type="time" name="start_hour" id="start_hour" required>
                <br>
                <label for="end_hour">A:</label>
                <input type="time" name="end_hour" id="end_hour" required>
                <br>
                <label for="date">Date:</label>
                <input type="date" name="date" id="date" required>
                <br>

                <button type="submit">Sauver</button>
                <button type="button" id="deleteBtn">Supprimer</button>
                <button type="button" id="congeBtn">Congé</button>
            </form>
            <div id="formError" class="alert alert-danger"></div>
        </div>
    </div>


    <div class="right-column">
        <h2>Liste des rendez-vous</h2>
        <ul id="rendezvousList"></ul>
    </div>

</div>

<!--
AJAX pour gérer la sauvegarde de RDV en DB  !
-->
<script>
    function loadRendezvousList() {
        $.ajax({
            type: 'GET',
            url: '../controllers/getList_rendezvous.php',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    const rendezvousList = response.data;
                    $('#rendezvousList').empty(); // Clear the list before appending new items

                    rendezvousList.forEach(function (rendezvous) {
                        // Create a list item for each rendezvous and append it to the rendezvous list
                        const listItem = $('<li>')
                            .text(rendezvous.name + ' - ' + rendezvous.date + ' - ' + rendezvous.start_hour + ' - ' + rendezvous.end_hour)
                            .data('rendezvous', rendezvous)  // set le data du rdv a chaque item
                            .appendTo('#rendezvousList');
                    });
                } else {
                    console.error('Error occurred while fetching rendezvous list:', response.message);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Error occurred while fetching rendezvous list:', textStatus, errorThrown);
            }
        });
    }

    $(document).ready(function () {
        const calendar = new Calendar("calendar", "rendezvousFormElem");
        loadRendezvousList(); // Load the rendezvous list when the page is loaded

        $('#rendezvousList').on('click', 'li', function () {
            const rendezvousData = $(this).data('rendezvous');
            calendar.fillFormFields(new Date(rendezvousData.date), rendezvousData);
            $('#action').val('update');
            $('#id').val(rendezvousData.id);
        });

        function showError(message) {
            $('#formError').text(message).show();
        }

        function clearError() {
            $('#formError').hide();
        }

        $('#rendezvousFormElem').on('submit', function (e) {
            e.preventDefault();

            if (!this.checkValidity()) {
                showError('Please fill out all required fields correctly.');
                return;
            }

            $.ajax({
                type: 'POST',
                url: '../controllers/save_rendezvous.php',
                data: $(this).serialize(),
                success: function (response) {
                    if (response.status === 'success') {
                        console.log('Rendez-vous sauvé:', response);
                        calendar.clearFormInputs(); // Clear the form inputs after successful save
                        loadRendezvousList(); // Reload the rendezvous list after a successful save/update/delete
                    } else {
                        showError(response.message);
                    }
                },

            });
        });
        $('#deleteBtn').on('click', function () {
            $('#action').val('delete');
            $('#rendezvousFormElem').submit();
        });
    });
</script>
<script src="../public/js/calendar.js"></script> <!-- le script js pour l'affichage du calendrier -->
</body>
</html>
