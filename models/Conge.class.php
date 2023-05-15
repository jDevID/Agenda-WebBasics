<?php
require_once('../data/DAL.class.php');

class Conge extends DAL {

    private function executeCongeQuery($sql, $params): bool
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

    public function creerConge($date): bool {
        $sql = "INSERT INTO conge (date) VALUES (:date)";
        $params = [':date' => $date];

        return $this->executeCongeQuery($sql, $params);
    }

    public function supprimerCongeByDate(string $date): bool {
        $sql = "DELETE FROM conge WHERE date = :date";
        $params = [':date' => $date];

        return $this->executeCongeQuery($sql, $params);
    }


    public function getAllDates() {
        $query = "SELECT date FROM conge ORDER BY date";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        // FETCH_COLUMN retourne un array indexé par col num
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function checkCongeByDate($date) : bool {
        $query = "SELECT * FROM conge WHERE date = :date";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function findByDate($date) {
        $query = "SELECT * FROM conge WHERE date = :date";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return null;
        }
    }
}
