<?php
require_once('../data/RendezvousDAL.class.php');

class Rendezvous
{
    private int $id;
    private string $description;
    private string $date;
    private string $start_hour;
    private string $end_hour;
    private int $user_id;
    private DateTimeZone $timezone;


    /**
     * @throws Exception
     */
    public function __construct(
        int           $id,
        string        $description,
        string        $date,
        string        $start_hour,
        string        $end_hour,
        int           $user_id,
        string        $timezone)
    {
        $this->id = $id;
        $this->description = $description;
        $this->date = $date;
        $this->start_hour = $start_hour;
        $this->end_hour = $end_hour;
        $this->user_id = $user_id;
        $this->timezone = new DateTimeZone($timezone);

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

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

}

?>
