<?php

namespace App\Domain\Factory;

use App\Domain\DTO\ReservationDTO;
use App\Domain\Entity\Reservation;
use App\Domain\Entity\Room;
use App\Domain\Enum\ReservationStatusEnum;
use Carbon\Carbon;
use Symfony\Component\Security\Core\User\UserInterface;

class BookingBuilder
{
    public function build(
        ?ReservationDTO $data,
        ?Room $room,
        UserInterface $user
    ): Reservation {
        $startDateParsed = Carbon::parse($data->getStartDate());
        $endDateParsed = Carbon::parse($data->getEndDate());

        $daysDiff = $startDateParsed->diffInDays($data->getEndDate());

        $reservation = new Reservation();
        $reservation->setRoom($room);
        $reservation->setUser($user);
        $reservation->setStatus(ReservationStatusEnum::RESERVED->value);
        $reservation->setStartDate($startDateParsed);
        $reservation->setEndDate($endDateParsed);
        $reservation->setPrice($room->getPrice() * $daysDiff);

        $reservation->getRoom()->setVacancy(false);

        return $reservation;
    }
}
