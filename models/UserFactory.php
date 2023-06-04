<?php
require_once('../models/Toast.class.php');
require_once('User.class.php');

class UserFactory
{
    private UserDAL $userDAL;

    public function __construct(UserDAL $userDAL)
    {
        $this->userDAL = $userDAL;
    }

    // Crée un nouvel utilisateur après validation
    public function createUser(int    $id = -1,
                               string $username = '',
                               string $password = '',
                               string $role = ''): User
    {
        $this->validateUserDetails($username, $password);
        $this->validateUserRole($role);
        $this->validateUsernameAvailability($username);

        // Hache le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        return new User($id, $username, $hashedPassword, $role);
    }

    public function createUserForLogin(string $username = '', string $password = ''): User
    {
        $this->validateUserDetails($username, $password);
        return new User(-1, $username, $password, '');
    }

    private function validateUserDetails(string $username, string $password): void
    {
        if (strlen($username) < 4 || strlen($username) > 25 || preg_match('/\d/', $username) || preg_match('/[^\p{L} ]/u', $username)) {
            throw new Exception('Le nom d\'utilisateur doit comporter entre 4 et 25 caractères et ne peut pas contenir de symboles ou de nombres.');
        }

        // Validate password
        if (strlen($password) < 6 || strlen($password) > 20 || !preg_match('/[A-Z]/', $password) || !preg_match('/\d/', $password) || preg_match('/[<>=\/\\\]/', $password) || preg_match('/\s/', $password)) {
            throw new Exception('Le mot de passe doit comprendre entre 6 et 20 caractères, inclure au moins une lettre majuscule, un chiffre et ne peut pas contenir d\'espace, <, >, \', =, /, \\ .');
        }
    }

    private function validateUsernameAvailability(string $username): void
    {
        if ($this->userDAL->checkUserExists($username)) {
            throw new Exception("Ce nom d'utilisateur est déjà pris.");
        }
    }

}

?>
