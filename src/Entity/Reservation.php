<?php

namespace App\Entity;

use App\Entity\Base\EntityBase;
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
    #[Groups(['booking_list'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'user_id', nullable: false)]
    #[Groups(['booking_list'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'reservation')]
    #[ORM\JoinColumn(name: 'room_id', nullable: false)]
    #[Groups(['booking_list'])]
    private ?Room $room = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[SerializedName('status')]
    #[Groups(['booking_list'])]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[SerializedName('start_date')]
    #[Groups(['booking_list'])]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[SerializedName('end_date')]
    #[Groups(['booking_list'])]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column]
    #[SerializedName('price')]
    #[Groups(['booking_list'])]
    private ?int $price = null;

    #[ORM\OneToOne(mappedBy: 'reservation', cascade: ['persist', 'remove'])]
    #[Groups(['booking_list'])]
    private ?BookingReview $bookingReview = null;

    #[ORM\OneToOne(mappedBy: 'reservation', cascade: ['persist', 'remove'])]
    #[Groups(['booking_list'])]
    private ?Payments $payments = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(['booking_list'])]
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    #[Groups(['booking_list'])]
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

    public function getStartDate(): ?string
    {
        return $this->start_date->format('d/m/Y');
    }

    public function setStartDate(?\DateTimeInterface $start_date): void
    {
        $this->start_date = $start_date;
    }

    public function getEndDate(): ?string
    {
        return $this->end_date->format('d/m/Y');
    }

    public function setEndDate(?\DateTimeInterface $end_date): void
    {
        $this->end_date = $end_date;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function getBookingReview(): ?BookingReview
    {
        return $this->bookingReview;
    }

    public function setBookingReview(BookingReview $bookingReview): static
    {
        // set the owning side of the relation if necessary
        if ($bookingReview->getReservation() !== $this) {
            $bookingReview->setReservation($this);
        }

        $this->bookingReview = $bookingReview;

        return $this;
    }

    public function getPayments(): ?Payments
    {
        return $this->payments;
    }

    public function setPayments(Payments $payments): static
    {
        if ($payments->getReservation() !== $this) {
            $payments->setReservation($this);
        }

        $this->payments = $payments;

        return $this;
    }
}
