<?php

namespace App\Infrastructure\Service;

use App\Domain\DTO\ReservationDTO;
use App\Domain\Entity\Room;
use App\Domain\Enum\ReservationStatusEnum;
use App\Domain\Exception\DateIsPastException;
use App\Domain\Exception\ReservationNotFound;
use App\Domain\Exception\RoomAlreadyBooked;
use App\Domain\Factory\BookingBuilder;
use App\Domain\Repository\ReservationRepository;
use App\Domain\Repository\RoomRepository;
use App\Domain\Repository\UserRepository;
use Carbon\Carbon;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class BookingService
{
    public function __construct(
        private UserRepository        $userRepository,
        private RoomRepository        $roomRepository,
        private BookingBuilder        $bookingBuilder,
        private ReservationRepository $reservationRepository,
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

        $room = $this->roomRepository->findOneBy(['roomNumber' => $data?->getRoom()]);

        $this
            ->checkBookedRoom($room)
            ->isPastDate($data->getStartDate())
            ->checkEndDateIncorrect($data->getStartDate(), $data->getEndDate());

        $reservation = $this->bookingBuilder->build($data, $room, $userReserving);

        $this->reservationRepository->save($reservation);
    }

    /**
     * @throws ReservationNotFound
     */
    public function cancel(?int $reservation): void
    {
        $reservationObj = $this->reservationRepository->find($reservation);

        if (!$reservationObj) {
            throw new ReservationNotFound('Reservation not found');
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
            throw new ReservationNotFound('Reservation not found');
        }

        $reservationObj->setStatus(ReservationStatusEnum::FINISHED->value);

        $room = $this->roomRepository->find($reservationObj->getRoom());
        $room->setVacancy(true);

        $this->reservationRepository->save($reservationObj);
    }

    /**
     * @throws RoomAlreadyBooked
     */
    public function checkBookedRoom(?Room $room): self
    {
        if (empty($room) || !$room->isVacancy()) {
            throw new RoomAlreadyBooked($room);
        }

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function isPastDate(string $startDate): self
    {
        $brasilTimezone = new \DateTimeZone('America/Sao_Paulo');

        $currentDateTime = Carbon::now($brasilTimezone);

        $startDate = Carbon::parse($startDate, $brasilTimezone);

        if ($startDate < $currentDateTime) {
            throw new DateIsPastException('Choose a future date to start your reservation!');
        }

        return $this;
    }

    public function checkEndDateIncorrect(string $startDate, string $endDate): self
    {
        $brasilTimezone = new \DateTimeZone('America/Sao_Paulo');

        $currentDateTime = Carbon::now($brasilTimezone);

        $startDate = Carbon::parse($startDate, $brasilTimezone);
        $endDate = Carbon::parse($endDate, $brasilTimezone);

        if ($endDate < $startDate) {
            throw new DateIsPastException('Choose a future date to finish your reservation!');
        }

        return $this;
    }
}
