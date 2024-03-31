<?php

namespace App\Domain\Factory;

use App\Domain\DTO\ReservationDTO;
use App\Domain\DTO\ReservationUpdateDTO;
use App\Domain\Entity\Reservation;
use App\Domain\Entity\Room;
use App\Domain\Enum\ReservationStatusEnum;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

class BookingBuilder
{
    public function createReservation(
        ?ReservationDTO $reservationData,
        ?Room $room,
        UserInterface $user
    ): Reservation {
        $parsedDates = $this->parseAndCalculateDays($reservationData->getStartDate(), $reservationData->getEndDate());

        $reservation = new Reservation();
        $reservation->setRoom($room);
        $reservation->setUser($user);
        $reservation->setStatus(ReservationStatusEnum::RESERVED->value);
        $reservation->setStartDate($parsedDates[0]);
        $reservation->setEndDate($parsedDates[1]);
        $reservation->setPrice($reservation->getRoom()->getPrice() * $parsedDates[2]);

        $reservation->getRoom()->setVacancy(false);

        return $reservation;
    }

    public function updateReservation(
        ?ReservationUpdateDTO $reservationData,
        ?Reservation $reservation,
    ): Reservation {
        $parsedDates = $this->parseAndCalculateDays($reservationData->getStartDate(), $reservationData->getEndDate());

        $reservation->setStartDate($parsedDates[0]);
        $reservation->setEndDate($parsedDates[1]);
        $reservation->setPrice($reservation->getRoom()->getPrice() * $parsedDates[2]);

        return $reservation;
    }

    private function parseAndCalculateDays(?string $start, ?string $end): ArrayCollection
    {
        $parsedStart = Carbon::parse($start);
        $parsedEnd = Carbon::parse($end);
        $diffDays = $parsedStart->diffInDays($parsedEnd);

        return new ArrayCollection([
            $parsedStart->toDate(),
            $parsedEnd->toDate(),
            $diffDays,
        ]);
    }
}