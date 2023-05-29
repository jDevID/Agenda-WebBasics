<?php

require_once('../data/CongeDAL.class.php');

class Conge
{
    private CongeDAL $dal;
    private int $id;
    private string $date;


    /**
     * @throws Exception
     */
    public function __construct(CongeDAL $dal,
                                   int $id = -1,
                                   string $date = '')
    {
        $this->dal = $dal;
        $this->id = $id;
        $this->setDate($date);
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getDateForMySQL(): string
    {
        $dateObj = DateTime::createFromFormat('d-m-Y', $this->date);
        return $dateObj->format('Y-m-d');
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setDate(string $date): void
    {
        $dateObj = DateTime::createFromFormat('Y-m-d', $date);
        $this->date = $dateObj->format('d-m-Y');
    }

    /**
     * @throws Exception
     */
    public function create(): bool {
        try {
            if ($this->checkHoliday($this->getDateForMySQL())) {
                throw new Exception('Holiday already exists on this date.');
            }
            return $this->dal->createHoliday($this);
        } catch (PDOException $e) {
            throw new Exception('Database error: ' . $e->getMessage());
        }
    }

    public function delete(): bool {
        return $this->dal->deleteHolidayByDate($this);
    }

    public function getAll(): array {
        return $this->dal->getAllDates();
    }

    public function checkHoliday(string $date): bool {
        // Change date format to 'd-m-Y' before checking
        $dateObj = DateTime::createFromFormat('Y-m-d', $date);
        $dateForCheck = $dateObj->format('d-m-Y');

        return $this->dal->checkHolidayByDate($dateForCheck);
    }

    /**
     * @throws Exception
     */
    public function findByDate(string $date): ?Conge {
        $result = $this->dal->findHolidayByDate($date);

        if ($result) {
            return new Conge($this->dal, $result['id'], $result['date']);
        } else {
            return null;
        }
    }
}

?>


