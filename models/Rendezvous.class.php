<?php
require_once '../data/DAL.class.php';

class Rendezvous extends DAL
{ // Classe Rendez-vous hérite du PDO par la DAL
    private string $name;
    private string $description;
    private string $date;
    private string $start_hour;
    private string $end_hour;
    private int $client_id;
    private int $user_id;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getStartHour(): string
    {
        return $this->start_hour;
    }

    public function getEndHour(): string
    {
        return $this->end_hour;
    }

    public function getClient(): int
    {
        return $this->client_id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @throws Exception
     */
    public function getStatus(): string
    {
        // determine status based on current date/time
        $now = new DateTime();
        if ($now > new DateTime($this->date . ' ' . $this->end_hour)) {
            $status = 'passé';
        } else {
            $status = 'futur';
        }
        return $status;
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

    public function getEmail(): ?string
    {
        // Fetch the client from the database using the client_id
        $clientQuery = "SELECT email FROM client WHERE id = :client_id";
        $stmt = $this->conn->prepare($clientQuery);
        $stmt->execute([':client_id' => $this->client_id]);

        // Fetch the email from the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // If no result was found, return null
        if (!$result) {
            return null;
        }

        // Return the email
        return $result['email'];
    }


}
