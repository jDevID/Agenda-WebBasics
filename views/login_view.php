<!DOCTYPE html>
<html lang="">
<head>
    <title>Login</title>
    <link rel="icon" href="../fav.ico" type="image/x-icon">
</head>
<body>
    <h1>Login</h1>
    <form action="../controllers/login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <input type="submit" value="Login">
    </form>
    <p>Don't have an account? <a href="../controllers/register.php">Register</a></p>
</body>
</html>
