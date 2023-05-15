<?php
require_once('../data/DAL.class.php');

class Client extends DAL { # abstract DAL.class.php = héritage du PDO

    private function executeClientQuery($sql, $params): bool
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $executionSuccess = $stmt->execute($params);
            if (!$executionSuccess) {
                throw new Exception("Error: " . implode(" - ", $stmt->errorInfo()));
            }
            return true;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getRendezvousForClient($clientId): array
    {
        if ($clientId === null) {
            error_log('pas d\'id client fourni pour générer la liste de rdv par id');
            return [];
        }

        $sql = "SELECT * FROM rendezvous WHERE client_id = :client_id";
        $params = [':client_id' => $clientId];

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }
    public function getAllClientsArray(): array
    {
        $query = "SELECT * FROM client ORDER BY name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function creerClient($name, $email): bool
    {
        $sql = "INSERT INTO client (name, email) 
                VALUES (:name, :email)";
        $params = [
            ':name' => $name,
            ':email' => $email,
        ];

        return $this->executeClientQuery($sql, $params);
    }

    public function modifierClientById($id, $name, $email): bool
    {
        $sql = "UPDATE client SET name = :name, email = :email WHERE id = :id";
        $params = [
            ':id' => $id,
            ':name' => $name,
            ':email' => $email,
        ];

        return $this->executeClientQuery($sql, $params);
    }

    public function supprimerClientById(int $id): bool
    {
        $sql = "DELETE FROM client WHERE id = :id";
        $params = [':id' => $id];

        return $this->executeClientQuery($sql, $params);
    }

}