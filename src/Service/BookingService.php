<?php

namespace App\Service;

use App\DTO\ReservationDTO;
use App\Entity\Room;
use App\Enum\ReservationStatusEnum;
use App\Exception\DateIsPasteException;
use App\Exception\ReservationNotFound;
use App\Exception\RoomAlreadyBooked;
use App\Factory\BookingBuilder;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * @throws RoomAlreadyBooked
     * @throws \Exception
     */
    public function createBooking(
        UserInterface $user,
        ?ReservationDTO $data,
    ): void {
        $userReserving = $this->userRepository->findOneBy(['email' => $user->getUserIdentifier()]);

        $room = $this->roomRepository->find($data?->getRoom());

        $this->checkBookedRoom($room);

        $this->isPastDate($data->getStartDate());

        $reservation = $this->bookingBuilder->build($data, $room, $userReserving);

        $room->setVacancy(false);

        $this->reservationRepository->save($reservation);
    }

    /**
     * @throws ReservationNotFound
     */
    public function cancel(?int $reservation): void
    {
        $reservationObj = $this->reservationRepository->find($reservation);

        if (!$reservationObj) {
            throw new ReservationNotFound('Reservation not found', Response::HTTP_NOT_FOUND);
        }

        $reservationObj->setStatus(ReservationStatusEnum::CANCELED->value);

        $room = $this->roomRepository->find($reservationObj->getRoom());
        $room->setVacancy(true);

        $this->reservationRepository->save($reservationObj);
    }

    /**
     * @throws ReservationNotFound
     */
    public function finished(?int $reservation): void
    {
        $reservationObj = $this->reservationRepository->find($reservation);

        if (!$reservationObj) {
            throw new ReservationNotFound('Reservation not found', Response::HTTP_NOT_FOUND);
        }

        $reservationObj->setStatus(ReservationStatusEnum::FINISHED->value);

        $room = $this->roomRepository->find($reservationObj->getRoom());
        $room->setVacancy(false);

        $this->reservationRepository->save($reservationObj);
    }

    /**
     * @throws RoomAlreadyBooked
     */
    public function checkBookedRoom(?Room $room): void
    {
        if (empty($room) || !$room->isVacancy()) {
            throw new RoomAlreadyBooked($room);
        }
    }

    /**
     * @throws \Exception
     */
    public function isPastDate(string $startDate): void
    {
        if (new \DateTime($startDate) < new \DateTime()) {
            throw new DateIsPasteException('Choose a future date to start your reservation!');
        }
    }
}
