<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgendApp | Rendez-vous</title>
    <script src="../public/js/formulaire.js"></script>
    <script src="../public/js/listeRendezvous.js"></script>
    <script src="../public/js/calendrier.js"></script>
    <link rel="stylesheet" href="../public/css/calendar.css">
    <!-- JQuery librairie Js, permet la manipulation aisée de documents HTML -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>


<body>
<div id="calendarContainer" class="calendar-container">
    <div id="calendar"></div>
    <div class="calendar-controls">
        <button id="btn_moisPrecedentId">Précédent</button>
        <button id="btn_moisSuivantId">Suivant</button>
    </div>
</div>


<div class="content">
    <div class="left-column">
        <div id="rendezvousForm" class="rendezvous-form">
            <h2>Edition Rendez-vous</h2>

            <form id="rendezvousFormElem" action="../controllers/crud_rendezvous.php" method="post">
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

                <button type="submit" id="saveUpdateBtn" >Sauver</button>
                <button type="button" id="deleteBtn">Supprimer</button>
                <button type="submit" id="congeBtn">Congé</button>
            </form>
            <div id="formError" class="alert alert-danger"></div>

        </div>
    </div>


    <div class="right-column">
        <h2>Liste des rendez-vous</h2>
        <ul id="rendezvousList"></ul>
    </div>

</div>


<script>

    let calendar;
    let formulaire;
    let listeRendezvous;
    // Assignation des classes aux id dans la vue
    document.addEventListener("DOMContentLoaded", function () {
        // L'ordre a de l'importance selon les dépendances de chaque component
        formulaire = new Formulaire("rendezvousFormElem");
        listeRendezvous = new ListeRendezvous("rendezvousList");
        calendar = new Calendrier("calendar");

        listeRendezvous.assignerDependances(calendar, formulaire);
        calendar.assignerDependances(listeRendezvous, formulaire);

        formulaire.initialize();
        listeRendezvous.initialize();
        listeRendezvous.refreshRendezvousListe();

        calendar.initCalendrier();
        calendar.cycleMoisAnnee();
    });

</script>

</body>
</html>
