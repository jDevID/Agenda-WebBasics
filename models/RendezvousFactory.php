<?php
require_once('../models/Toast.class.php');
require_once('Rendezvous.class.php');

class RendezvousFactory
{
    private array $motsInterdits = ["Gloubiboulga", "Chapi-Chapo"];

    public function createRendezvous(RendezvousDAL $dal,
                                     int           $id = -1,
                                     string        $description = '',
                                     string        $date = '',
                                     string        $start_hour = '',
                                     string        $end_hour = '',
                                     int           $user_id = -1,
                                     string        $timezone = 'Europe/Paris'): Rendezvous
    {
        $this->validateDescription($description);
        $this->validateDate($date);
        $this->validateTime($start_hour, $timezone);
        $this->validateTime($end_hour, $timezone);


        if (!$dal->isTimeSlotAvailable($start_hour, $end_hour, $date, $user_id)) {
            throw new Exception('Ce créneau horaire n\'est pas disponible.');
        }


        if (strtotime($date . ' ' . $start_hour) >= strtotime($date . ' ' . $end_hour)) {
            throw new Exception('L\'heure de départ ne peut être égal ou supérieur à l\'heure de fin.');
        }


        return new Rendezvous($id, $description, $date, $start_hour, $end_hour, $user_id, $timezone);
    }

    private function validateDescription(string $description): void
    {
        if (strlen($description) < 20 || strlen($description) > 300) {
            throw new Exception('La longueur de la description doit être comprise entre 20 et 300 caractères.');
        }

        if (ucfirst($description) !== $description) {
            throw new Exception('La description doit commencer par une lettre majuscule.');
        }

        foreach ($this->motsInterdits as $word) {
            if (strpos($description, $word) !== false) {
                throw new Exception('La description ne doit pas contenir de langage grossier ou familier.');
            }
        }
        if (ctype_digit(substr($description, 0, 1))) {
            throw new Exception('La description ne doit pas commencer par un chiffre.');
        }

        if (preg_match('/[<>=\/\\\]/', $description)) {
            throw new Exception('La description ne peut pas contenir les symboles suivants: &lt;, &gt;, =, /, \' ');
        }

    }

    /**
     * @throws Exception
     */
    private function validateDate(string $date): void
    {
        if (empty($date)) {
            throw new Exception('Pas de date fournie.');
        }

        $inputDate = DateTime::createFromFormat('d-m-Y', $date);
        if ($inputDate === false) {
            throw new Exception('Invalid date format. It should be DD-MM-YYYY.');
        }

        $currentDate = new DateTime('now');
        $currentDate->setTime(0, 0); // Set time to start of day

        if ($inputDate < $currentDate) {
            throw new Exception('La date ne peut pas être dans le passé.');
        }
    }

    private function validateTime(string $time, string $timezone): void
    {
        $time_dt = new DateTime($time, new DateTimeZone($timezone));
        if ($time_dt->format('H:i') > "22:00" || $time_dt->format('H:i') < "06:00") {
            throw new Exception('L\'heure doit être comprise entre 06:00 et 22:00.');
        }

        $time_dt->format('H:i');
    }


}

?>
