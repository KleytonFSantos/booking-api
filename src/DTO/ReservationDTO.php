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

    /**
     * Get the value of room.
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Set the value of room.
     *
     * @return self
     */
    public function setRoom($room)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Get the value of startDate.
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set the value of startDate.
     *
     * @return self
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get the value of endDate.
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set the value of endDate.
     *
     * @return self
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }
}
