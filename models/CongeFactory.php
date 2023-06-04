<?php
require_once '../models/init.php';

class CongeFactory
{
    private CongeDAL $congeDAL;
    private RendezvousDAL $rendezvousDAL;

    public function __construct(CongeDAL $congeDAL, RendezvousDAL $rendezvousDAL)
    {
        $this->congeDAL = $congeDAL;
        $this->rendezvousDAL = $rendezvousDAL;
    }


    public function createConge(CongeDAL $dal, int $id = -1, string $date = ''): Conge
    {
        $this->validateDate($date);

        if ($dal->checkHolidayByDate($date)) {
            throw new Exception('Holiday already exists on this date.');
        }

        return new Conge($id, $date);
    }

    public function populateConge(int $id, string $date): Conge
    {
        return new Conge($id, $date);
    }
    /**
     * @throws Exception
     */
    private function validateDate(string $date): void
    {
        if (empty($date)) {
            throw new Exception('No date provided.');
        }

        $inputDate = DateTime::createFromFormat('Y-m-d', $date);
        if ($inputDate === false) {
            throw new Exception('Invalid date. Expected format: Y-m-d.');
        }

        $currentDate = new DateTime('now');
        $currentDate->setTime(0, 0);

        if ($inputDate < $currentDate) {
            throw new Exception('The date cannot be in the past.');
        }

        if ($this->rendezvousDAL->existsRendezvousOnDate($inputDate)) {
            throw new Exception('Cannot set a Conge on a Rendezvous date.');
        }
    }
}

?>