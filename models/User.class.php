<?php
require_once('../data/DAL.class.php');

# usage des prepared statements de type PDOStatement - $stmt (contre SQLi)
# pas de validation ni de sanitization d'input (rien contre XSS et CSRF)

class User extends DAL # abstract DAL.class.php = héritage du PDO
{
    public function register($username, $password)
    {
        # bcrypt algorithm par défaut (blowfish cipher), simple
        # ne jamais sauver plaintext
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $this->conn->prepare($sql); # prepared statement et méthode appartenant à objet PDO
        $stmt->execute([':username' => $username, ':password' => $hashed_password]);
    }

    public function login($username, $password)
    {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($sql); # prepared statement et méthode appartenant à objet PDO
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // TODO: Better handling...
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
}

?>
