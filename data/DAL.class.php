<?php
abstract class DAL
{
    # PHP Data Objects bon ratio sécurité/flexibilité
    # (prepared statements et placeholders contre SQLi)
    protected PDO $conn;


    public function __construct()
    {
        $dsn = 'mysql:host=localhost;dbname=agenda_db;';
        $username = 'root';
        $password = '1225';

        try {
            $this->conn = new PDO($dsn, $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}
?>
