<?php

namespace App\Domain\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ReservationUpdateDTO
{
    #[Assert\Length(
        min: 8,
        minMessage: 'Escolha uma data válida',
    )]
    private ?string $startDate = null;

    #[Assert\Length(
        min: 8,
        minMessage: 'Escolha uma data válida',
    )]
    private ?string $endDate = null;

    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    public function setStartDate($startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    public function setEndDate($endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }
}
