<?php
require_once '../data/DAL.class.php';

class Rendezvous extends DAL {
    public function updateRendezvous($id, $name, $description, $date, $start_hour, $end_hour, $user_id): bool {
        $sql = "UPDATE rendezvous SET user_id = :user_id, name = :name, description = :description, date = :date, start_hour = :start_hour, end_hour = :end_hour WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        $params = [
            ':id' => $id,
            ':user_id' => $user_id,
            ':name' => $name,
            ':description' => $description,
            ':date' => $date,
            ':start_hour' => $start_hour,
            ':end_hour' => $end_hour
        ];

        if (!$stmt->execute($params)) {
            echo "Error: " . implode(" - ", $stmt->errorInfo());
            return false;
        }
        return true;
    }

    public function deleteRendezvous($id): bool {
        $sql = "DELETE FROM rendezvous WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            echo "Error: " . implode(" - ", $stmt->errorInfo());
            return false;
        }
        return true;
    }

    public function saveRendezvous($name, $description, $date, $start_hour, $end_hour, $user_id): bool {

        $sql = "INSERT INTO rendezvous (user_id, name, description, date, start_hour, end_hour) VALUES (:user_id, :name, :description, :date, :start_hour, :end_hour)";

        $stmt = $this->conn->prepare($sql);

        $params = [
            ':user_id' => $user_id,
            ':name' => $name,
            ':description' => $description,
            ':date' => $date,
            ':start_hour' => $start_hour,
            ':end_hour' => $end_hour
        ];

        if (!$stmt->execute($params)) {

            echo "Error: " . implode(" - ", $stmt->errorInfo());
            return false;
        }
        return true;
    }

    public function getRendezvousData($year, $month) {
        $query = "SELECT * FROM rendezvous WHERE YEAR(date) = :year AND MONTH(date) = :month";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->bindParam(':month', $month, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
