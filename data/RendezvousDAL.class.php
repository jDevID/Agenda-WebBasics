<?php
require_once '../data/DAL.class.php';
require_once '../models/Rendezvous.class.php';
require_once '../models/Conge.class.php';


class RendezvousDAL extends DAL
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function getAll(RendezvousFactory $factory, string $timezone): array
    {

        $sql = "SELECT * FROM rendezvous ORDER BY date, start_hour";
        $results = $this->fetchAll($sql);
        $rendezvous = [];

        foreach ($results as $row) {
            $rendezvous[] = $factory->createRendezvous(
                $this,
                $row['id'],
                $row['description'],
                $row['date'],
                $row['start_hour'],
                $row['end_hour'],
                $row['user_id'],
                $timezone,
                false
            );
        }

        return $rendezvous;
    }

    /**
     * @throws Exception
     */
    public function getUserNameByUserId($userId): string
    {
        $sql = "SELECT username FROM users WHERE id = :id";
        $params = [':id' => $userId];

        $result = $this->fetch($sql, $params);

        if ($result) {
            return $result['username'];
        } else {
            Toast::throwMessage("No user with ID: $userId");
            return '';
        }
    }

    public function insert(Rendezvous $rendezvous): bool
    {
        $sql = "INSERT INTO rendezvous (description, date, start_hour, end_hour, user_id) 
                VALUES (:description, :date, :start_hour, :end_hour, :user_id)";
        $params = [
            ':description' => $rendezvous->getDescription(),
            ':date' => $rendezvous->getDate(),
            ':start_hour' => $rendezvous->getStartHour(),
            ':end_hour' => $rendezvous->getEndHour(),
            ':user_id' => $rendezvous->getUserId(),
        ];

        return $this->executeQuery($sql, $params);
    }

    public function update(Rendezvous $rendezvous): bool
    {
        $sql = "UPDATE rendezvous SET  description = :description, date = :date, start_hour = :start_hour, end_hour = :end_hour WHERE id = :id and user_id = :user_id";
        $params = [
            ':id' => $rendezvous->getId(),
            ':description' => $rendezvous->getDescription(),
            ':date' => $rendezvous->getDate(),
            ':start_hour' => $rendezvous->getStartHour(),
            ':end_hour' => $rendezvous->getEndHour(),
            ':user_id' => $rendezvous->getUserId(),
        ];

        return $this->executeQuery($sql, $params);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM rendezvous WHERE id = :id";
        $params = [':id' => $id];

        return $this->executeQuery($sql, $params);
    }

    /**
     * @throws Exception
     */
    public function getRendezvousById(int $id): ?Rendezvous
    {
        $sql = "SELECT * FROM rendezvous WHERE id = :id";
        $params = [':id' => $id];

        $result = $this->fetch($sql, $params);

        if ($result) {
            return new Rendezvous(
                $result['id'],
                $result['description'],
                $result['date'],
                $result['start_hour'],
                $result['end_hour'],
                $result['user_id'],
                'Europe/Paris'
            );
        } else {
            return null;
        }
    }

    // check si 1 rdv en même temps
    public function isTimeSlotAvailable(string $start_hour, string $end_hour, string $date, int $user_id): bool
    {

        $sql = "SELECT COUNT(*) as count 
        FROM rendezvous
        WHERE user_id = :user_id AND date = :date AND (
            (start_hour <= :end_time AND end_hour >= :start_time) OR
            (start_hour >= :start_time AND end_hour <= :end_time)
        )";
        $params = [
            ':user_id' => $user_id,
            ':date' => $date,
            ':start_time' => $start_hour,
            ':end_time' => $end_hour,
        ];

        $result = $this->fetch($sql, $params);
        return $result && $result['count'] == 0;
    }

    public function overlapsWithHoliday(string $date): bool
    {
        $sql = "SELECT COUNT(*) FROM conge WHERE date = :date";
        $params = [':date' => $date];

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        $holiday_count = $stmt->fetchColumn();

        return $holiday_count > 0;
    }
}

?>
