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
    <script src="../public/js/calendar.js"></script> <!-- le script js pour l'affichage du calendrier -->
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
            <div id="formError" class="alert alert-danger" ></div>
        </div>
    </div>


    <div class="right-column">
        <h2>Liste des rendez-vous</h2>
        <ul id="rendezvousList"></ul>
        <script src="../public/js/getRendezvousList.js"></script>
    </div>

</div>

<!--
AJAX pour gérer la sauvegarde de RDV en DB  !
-->
<script>

    $(document).ready(function () {
        const calendar = new Calendar("calendar", "rendezvousFormElem");

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
                        // Reload the rendezvous list after a successful save/update/delete
                        calendar.reloadRendezvousList();
                    } else {
                        showError(response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('Il y a eu un problème lors de la sauvegarde du Rendez-vous:', textStatus, errorThrown);
                }
            });
        });


        $(document).on('click', '.delete-button', function () {
            const rendezvousId = $(this).data('id');
            $('#action').val('delete');
            $('#id').val(rendezvousId); // Set the hidden input field value to the rendezvous id
        });

    });
</script>

</body>
</html>
