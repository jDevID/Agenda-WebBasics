<?php

require_once('../data/CongeDAL.class.php');

class Conge
{
    private int $id;
    private string $date;


    /**
     * @throws Exception
     */
    public function __construct(int      $id,
                                string   $date)
    {
        $this->id = $id;
        $this->date = $date;
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

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

}

?>
