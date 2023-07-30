<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $roomNumber;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column]
    private ?bool $vacancy = null;

    #[ORM\OneToOne(mappedBy: 'room_id', cascade: ['persist', 'remove'])]
    private ?Reservation $reservation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isVacancy(): ?bool
    {
        return $this->vacancy;
    }

    public function setVacancy(bool $vacancy): static
    {
        $this->vacancy = $vacancy;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(Reservation $reservation): static
    {
        // set the owning side of the relation if necessary
        if ($reservation->getRoom() !== $this) {
            $reservation->setRoom($this);
        }

        $this->reservation = $reservation;

        return $this;
    }

    /**
     * Get the value of room_number.
     */
    public function getRoomNumber()
    {
        return $this->roomNumber;
    }

    /**
     * Set the value of roomNumber.
     *
     * @return self
     */
    public function setRoomNumber($roomNumber)
    {
        $this->roomNumber = $roomNumber;

        return $this;
    }
}
