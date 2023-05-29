<?php
class UserConnected extends User
{
    public function __construct(?int $id = null, ?string $username = null, ?string $role = null)
    {
        parent::__construct($id, $username, null, $role);
    }

    public function setPassword($password)
    {
        throw new Exception("Cette opération n'est pas permise.");
    }
}

?>

