<?php
require_once '../controllers/session_check.php';
require_once '../models/Conge.class.php';
require_once '../models/Rendezvous.class.php';
require_once '../validation/validator.php';

class DateValidator {
    public static function isValidDate($date, $startHour, $endHour, $format = 'Y-m-d'): bool
    {
        $d = DateTime::createFromFormat($format, $date);

        // La date est valide si:
        // 1. date dans un format correct
        // 2. pas de date passée
        $isValid = $d && $d->format($format) === $date && $d <= new DateTime();

        // 3. check congé: pas pendant un congé
        if ($isValid) {
            $conge = new Conge();
            if ($conge->findByDate($date)) {
                return false;
            }
        }

        // 4. check rdv: pas pdt un autre rdv (heures)
        if ($isValid) {
            $rdv = new Rendezvous();
            if ($rdv->findByDateAndTime($date, $startHour, $endHour)) {
                return false;
            }
        }
        // 5. check si rdv pdt heures de travail
        if ($isValid) {
            $hour = (int)$d->format('H');
            if ($hour < 6 || $hour >= 22) {
                return false;
            }
        }

        return $isValid;
    }
}
