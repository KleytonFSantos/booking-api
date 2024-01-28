<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['booking_list'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['booking_list'])]
    private ?int $roomNumber = null;

    #[ORM\Column]
    #[Groups(['booking_list'])]
    private ?int $price = null;

    #[ORM\Column]
    #[Groups(['booking_list'])]
    private ?bool $vacancy = null;

    #[ORM\OneToOne(mappedBy: 'room', cascade: ['persist', 'remove'])]
    private ?Reservation $reservation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function isVacancy(): ?bool
    {
        return $this->vacancy;
    }

    public function setVacancy(bool $vacancy): void
    {
        $this->vacancy = $vacancy;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(Reservation $reservation): void
    {
        // set the owning side of the relation if necessary
        if ($reservation->getRoom() !== $this) {
            $reservation->setRoom($this);
        }

        $this->reservation = $reservation;
    }

    public function getRoomNumber(): ?int
    {
        return $this->roomNumber;
    }

    public function setRoomNumber($roomNumber): void
    {
        $this->roomNumber = $roomNumber;
    }
}
