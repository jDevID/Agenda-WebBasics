<?php
require_once '../data/DAL.class.php';

class Rendezvous extends DAL
{ // Classe Rendez-vous hérite du PDO par la DAL


    /** Méthode utilitaire RDV
     * @throws Exception
     */
    private function executeRendezvousQuery($sql, $params): bool
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $executionSuccess = $stmt->execute($params);
            // si problème lance l'exception
            if (!$executionSuccess) {
                throw new Exception("Error: " . implode(" - ", $stmt->errorInfo()));
            }
            return true; // OK

        } catch (Exception $e) {
            // Rapport d'exception
            error_log($e->getMessage());
            return false;
        }
    }

    public
    function getAllRendezvousArray(): array
    {
        $query = "SELECT * FROM rendezvous ORDER BY date, start_hour";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        // fetchAll retourne un tableau de résultat
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllRendezvousForDay($date): array
    {
        $query = "SELECT * FROM rendezvous WHERE date = :date ORDER BY start_hour";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        public
    function getCountRendezvousByDate(): array
    {
        $query = "SELECT date, COUNT(*) as count FROM rendezvous GROUP BY date ORDER BY date";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        // fetchAll retourne un tableau de résultat
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    /** Créer un rendez-vous en DB
     * / @throws Exception
     */
    public
    function creerRendezvous($name, $description, $date, $start_hour, $end_hour, $client_id, $user_id): bool
    {
        $sql = "INSERT INTO rendezvous (name, description, date, start_hour, end_hour, client_id, user_id) 
                VALUES (:name, :description, :date, :start_hour, :end_hour, :client_id, :user_id)";
        $params = [
            ':name' => $name,
            ':description' => $description,
            ':date' => $date,
            ':start_hour' => $start_hour,
            ':end_hour' => $end_hour,
            ':client_id' => $client_id,
            ':user_id' => $user_id,
        ];

        return $this->executeRendezvousQuery($sql, $params);
    }


    /** Modifier un rendez-vous en DB
     * @throws Exception
     */
    public
    function modifierRendezvousById($id, $name, $description, $date, $start_hour, $end_hour, $client_id, $user_id): bool
    {
        $sql = "UPDATE rendezvous SET name = :name, description = :description, date = :date, start_hour = :start_hour, end_hour = :end_hour, client_id = :client_id WHERE id = :id and user_id = :user_id";
        $params = [
            ':id' => $id,
            ':name' => $name,
            ':description' => $description,
            ':date' => $date,
            ':start_hour' => $start_hour,
            ':end_hour' => $end_hour,
            ':client_id' => $client_id,
            ':user_id' => $user_id,
        ];
        // Envoi à la fonction utilitaire
        return $this->executeRendezvousQuery($sql, $params);
    }

    /** Deleter RDV
     * @throws Exception
     */
    public function annulerRendezvousById(int $id): bool
    {
        $sql = "DELETE FROM rendezvous WHERE id = :id";
        $params = [':id' => $id];

        return $this->executeRendezvousQuery($sql, $params);
    }
}

//    public function getRendezvousByMonth($year, $month)
//    {
//        $query = "SELECT * FROM rendezvous WHERE YEAR(date) = :year AND MONTH(date) = :month ORDER BY date, start_hour";
//        $stmt = $this->conn->prepare($query);
//        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
//        $stmt->bindParam(':month', $month, PDO::PARAM_INT);
//        $stmt->execute();
//
//        return $stmt->fetchAll(PDO::FETCH_ASSOC);
//    }
