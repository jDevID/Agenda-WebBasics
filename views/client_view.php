<!DOCTYPE html>
<html lang="">
<head>
    <title>View Clients</title>
    <link rel="icon" href="../fav.ico" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
<h1>Client List</h1>
<ul id="client_list"></ul>

<h1>Add Client</h1>
<form id="add_client_form">
    <label for="client_name">Name:</label><br>
    <input type="text" id="client_name" name="client_name" required><br>
    <label for="client_email">Email:</label><br>
    <input type="email" id="client_email" name="client_email" value="david@botton.ietc" required><br>
    <input type="submit" name="add_client" value="Add Client">
</form>

<h1>Delete Client</h1>
<form id="delete_client_form">
    <label for="client_id">Client ID:</label><br>
    <input type="number" id="client_id" name="client_id" required><br>
    <input type="submit" name="delete_client" value="Delete Client">
</form>

<script src="../public/js/clientele.js"></script>


<script>
    let clientele = new Clientele("client_list");
    clientele.refreshClientList();
</script>


</body>
</html>

