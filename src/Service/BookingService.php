<?php

namespace App\Service;

use App\DTO\ReservationDTO;
use App\Entity\Room;
use App\Exception\RoomAlreadyBooked;
use App\Factory\BookingBuilder;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class BookingService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly RoomRepository $roomRepository,
        private readonly BookingBuilder $bookingBuilder,
        private readonly ReservationRepository $reservationRepository,
    ) {
    }

    public function createBooking(
        UserInterface $user,
        ?ReservationDTO $data,
    ): void {
        $userReserving = $this->userRepository->findOneBy(['email' => $user->getUserIdentifier()]);

        $room = $this->roomRepository->find($data?->getRoom());

        $this->checkBookedRoom($room);

        $reservation = $this->bookingBuilder->build($data, $room, $userReserving);

        $room->setVacancy(true);

        $this->reservationRepository->save($reservation);
    }

    public function checkBookedRoom(Room $room)
    {
        if ($room->isVacancy()) {
            throw new RoomAlreadyBooked($room);
        }
    }
}
