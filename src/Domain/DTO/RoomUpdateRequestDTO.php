<?php

namespace App\Domain\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class RoomUpdateRequestDTO
{
    public const VACANCIES_CHOICES = [true, false];
    #[Assert\NotBlank(
        message: 'Escolha um numero válido',
    )]
    private ?int $roomNumber = null;

    #[Assert\NotBlank(
        message: 'Escolha um valor válido',
    )]
    private ?int $price = null;

    public function getRoomNumber(): ?int
    {
        return $this->roomNumber;
    }

    public function setRoomNumber(?int $roomNumber): void
    {
        $this->roomNumber = $roomNumber;
    }


    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }
}