<?php
class RendezvousValidator
{
    private array $errors = [];


    public function __construct($congesRepository, $rendezvousRepository)
    {
        $this->congesRepository = $congesRepository;
        $this->rendezvousRepository = $rendezvousRepository;
    }

    /**
     * @throws Exception
     */
    public function validate(Rendezvous $rendezvous): bool
    {
        $this->errors = []; // reset errors

        if (empty($rendezvous->getName())) {
            $this->errors[] = 'First name is required.';
        }

        if (!filter_var($rendezvous->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Invalid email address.';
        }

        if (!DateValidator::isValidDate($rendezvous->getDate(), $rendezvous->getStartHour(), $rendezvous->getEndHour())) {
            $this->errors[] = 'Invalid date or time.';
        }

        if (!in_array($rendezvous->getStatus(), ['pending', 'confirmed', 'cancelled'])) {
            $this->errors[] = 'Invalid status.';
        }


        // pas à une date passée
        if (new DateTime() > new DateTime($rendezvous->getDate())) {
            $this->errors[] = 'Cannot save or modify a rendezvous in the past.';
        }

        // pas annuler un rdv - de 24hrs avant : TODO in delete method

        // pas de rdv pdt un rdv
        $overlappingRendezvous = $this->rendezvousRepository->findOverlapping($rendezvous);
        if ($overlappingRendezvous) {
            $this->errors[] = 'Sauvegarde RDV annulée: Vous avez un rdv à cette date';
        }

        // Pas de rdv pendant un congé
        $congeOnSameDay = $this->congesRepository->findByDate($rendezvous->getDate());
        if ($congeOnSameDay) {
            $this->errors[] = 'Sauvegarde RDV annulée: Vous êtes en congé à cette date';
        }

        // pas de congé dans un congé => TODO in congé validator


        return count($this->errors) === 0;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}