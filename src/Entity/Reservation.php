<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation extends EntityBase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["booking_list"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'user_id', nullable: false)]
    #[Groups(["booking_list"])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'reservation')]
    #[ORM\JoinColumn(name: 'room_id', nullable: false)]
    #[Groups(["booking_list"])]
    private ?Room $room = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[SerializedName('status')]
    #[Groups(["booking_list"])]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[SerializedName('start_date')]
    #[Groups(["booking_list"])]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[SerializedName('end_date')]
    #[Groups(["booking_list"])]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column]
    #[SerializedName('price')]
    #[Groups(["booking_list"])]
    private ?int $price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(["booking_list"])]
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    #[Groups(["booking_list"])]
    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(Room $room): static
    {
        $this->room = $room;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(?\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(?\DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
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
}
