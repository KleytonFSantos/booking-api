<?php

namespace App\Factory;

use App\DTO\ReservationDTO;
use App\Entity\Reservation;
use App\Entity\Room;
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
        $reservation->setStartDate($startDateParsed);
        $reservation->setEndDate($endDateParsed);
        $reservation->setPrice($room->getPrice() * $daysDiff);

        return $reservation;
    }
}
