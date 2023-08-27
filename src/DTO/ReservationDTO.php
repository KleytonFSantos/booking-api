<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ReservationDTO
{
    private ?int $room;

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

    public function getRoom(): ?int
    {
        return $this->room;
    }

    public function setRoom(int $room): static
    {
        $this->room = $room;

        return $this;
    }

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
