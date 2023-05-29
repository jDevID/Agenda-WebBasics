<?php
class DAL
{
    protected PDO $conn;

    public function __construct()
    {
        $dsn = 'mysql:host=localhost;dbname=DavidBotton;charset=utf8mb4';
        $username = 'root';
        $password = '1225';

        try {
            $this->conn = new PDO($dsn, $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function executeQuery(string $sql, array $params = []): bool
    {
        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function fetch(string $sql, array $params = []): ?array
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result === false) {
                return null;
            }

            return $result;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}

?>
