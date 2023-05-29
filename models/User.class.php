<?php
require_once('../data/UserDAL.class.php');

class User
{
    private ?int $id;
    private ?string $username;
    private ?string $password;
    private ?string $role;

    public function __construct(?int $id = null, ?string $username = null, ?string $password = null, ?string $role = null)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }


    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

}

?>

