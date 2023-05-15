<?php

require_once '../models/Rendezvous.class.php';

class RendezvousRepository {
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Retrieve a rendezvous by its ID
    public function findRendezvousById($id): ?Rendezvous
    {
        $sql = 'SELECT * FROM rendezvous WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new Rendezvous($data);
        }

        return null;
    }

    // Insert a new rendezvous into the database
    public function createRendezvous(Rendezvous $rendezvous): bool
    {
        $sql = 'INSERT INTO rendezvous (name, description, date, start_hour, end_hour, client, user_id) 
                VALUES (:name, :description, :date, :start_hour, :end_hour, :client, :user_id)';
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'name' => $rendezvous->getName(),
            'description' => $rendezvous->getDescription(),
            'date' => $rendezvous->getDate(),
            'start_hour' => $rendezvous->getStartHour(),
            'end_hour' => $rendezvous->getEndHour(),
            'client' => $rendezvous->getClient(),
            'user_id' => $rendezvous->getUserId(),
        ]);
    }

    // Update an existing rendezvous in the database
    public function updateRendezvous(Rendezvous $rendezvous): bool
    {
        $sql = 'UPDATE rendezvous SET name = :name, description = :description, date = :date, start_hour = :start_hour, end_hour = :end_hour, client = :client 
                WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id' => $rendezvous->getId(),
            'name' => $rendezvous->getName(),
            'description' => $rendezvous->getDescription(),
            'date' => $rendezvous->getDate(),
            'start_hour' => $rendezvous->getStartHour(),
            'end_hour' => $rendezvous->getEndHour(),
            'client' => $rendezvous->getClient(),
        ]);
    }

    // Delete a rendezvous from the database
    public function deleteRendezvous(Rendezvous $rendezvous): bool
    {
        $sql = 'DELETE FROM rendezvous WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(['id' => $rendezvous->getId()]);
    }
}