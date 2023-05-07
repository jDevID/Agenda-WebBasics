<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgendApp | Rendez-vous</title>
    <link rel="stylesheet" href="../public/css/calendar.css">
    <!-- JQuery librairie Js, permet la manipulation aisée de documents HTML -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../public/js/calendar.js"></script> <!-- le script js pour l'affichage du calendrier -->
</head>

<!-- Contrôles du calendrier -->
<body>
    <button id="prevMonth">Précédent</button>
    <button id="nextMonth">Suivant</button>
    <div id="calendar"></div>
</body>

<div id="rendezvousForm" class="rendezvous-form">
    <h2>Edition Rendez-vous</h2>
    <form action="../controllers/save_rendezvous.php" method="post">
        <input type="hidden" name="id" id="id">
        <label for="name">Nom:</label>
        <input type="text" name="name" id="name" required>
        <br>
        <label for="description">Description:</label>
        <textarea name="description" id="description"></textarea>
        <br>
        <label for="date">Date:</label>
        <input type="date" name="date" id="date" required>
        <br>
        <label for="start_hour">De:</label>
        <input type="time" name="start_hour" id="start_hour" required>
        <br>
        <label for="end_hour">A:</label>
        <input type="time" name="end_hour" id="end_hour" required>
        <br>
        <button type="submit">Sauver</button>
    </form>
</div>

</html>
