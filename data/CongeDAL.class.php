<?php
require_once '../data/DAL.class.php';
require_once '../models/Conge.class.php';

class CongeDAL extends DAL
{

    public function __construct()
    {
        parent::__construct();
    }

    public function createHoliday(Conge $conge): bool
    {
        $sql = "INSERT INTO conge (date) VALUES (:date)";
        $params = [':date' => $conge->getDate()];

        return $this->executeQuery($sql, $params);
    }

    public function deleteHolidayByDate(Conge $conge): bool
    {
        $sql = "DELETE FROM conge WHERE id = :id";
        $params = [':id' => $conge->getId()];

        return $this->executeQuery($sql, $params);
    }

    public function getAllDates(): array
    {
        $sql = "SELECT * FROM conge ORDER BY date";
        $results = $this->fetchAll($sql);
        $conges = [];

        foreach ($results as $row) {
            $conges[] = new Conge($row['id'], $row['date']);
        }

        return $conges;
    }

    public function checkHolidayByDate(string $date): bool
    {
        $sql = "SELECT * FROM conge WHERE date = :date";
        $params = [':date' => $date];
        $result = $this->fetchAll($sql, $params);
        return $result != null;
    }

    public function findHolidayByDate(string $date): ?array
    {
        $sql = "SELECT * FROM conge WHERE date = :date";
        $params = [':date' => $date];
        return $this->fetchAll($sql, $params);
    }
}

?>