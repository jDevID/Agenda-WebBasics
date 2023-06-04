<?php
/*  *   *   * DATA - Congé  *   *   *
 *
 */
require_once '../models/init.php';
require_once '../models/CongeFactory.php';

class CongeDAL extends DAL
{

    public function __construct()
    {
        parent::__construct();
    }

    public function createConge(Conge $conge): bool
    {
        $sql = "INSERT INTO conge (date) VALUES (:date)";
        $params = [':date' => $conge->getDate()];

        return $this->executeQuery($sql, $params);
    }

    public function delete($id): bool
    {
        $sql = "DELETE FROM conge WHERE id = :id";
        $params = [':id' => $id];

        return $this->executeQuery($sql, $params);
    }

    public function getCongeById($id): ?Conge
    {
        $sql = "SELECT * FROM conge WHERE id = :id";
        $params = [':id' => $id];
        $result = $this->fetch($sql, $params);

        if ($result) {
            $rendezvousDAL = new RendezvousDAL();
            $congeFactory = new CongeFactory($this, $rendezvousDAL);
            return $congeFactory->populateConge($result['id'], $result['date']);
        }

        return null;
    }

    public function getAll(CongeFactory $factory): array
    {
        $sql = "SELECT * FROM conge ORDER BY date";
        $results = $this->fetchAll($sql);
        $conges = [];

        foreach ($results as $row) {
            $conges[] = $factory->populateConge($row['id'], $row['date']);
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

    public function update(Conge $conge): bool
    {
        $sql = "UPDATE conge SET date = :date WHERE id = :id";
        $params = [
            ':date' => $conge->getDate(),
            ':id' => $conge->getId(),
        ];

        return $this->executeQuery($sql, $params);
    }

}

?>
