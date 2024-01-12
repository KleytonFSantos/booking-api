<?php

namespace App\Tests;

use App\DTO\ReservationDTO;
use App\Entity\Room;
use App\Exception\DateIsPastException;
use App\Exception\RoomAlreadyBooked;
use App\Factory\BookingBuilder;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use App\Service\BookingService;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CreateBookingTest extends KernelTestCase
{
    /**
     * @throws RoomAlreadyBooked
     */
    public function testRoomExistsAndIsVacant(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $reservationRepositoryMock = $this->createMock(ReservationRepository::class);
        $roomRepositoryMock = $this->createMock(RoomRepository::class);
        $bookBuilderMock = $this->createMock(BookingBuilder::class);

        $bookingService = new BookingService(
            $userRepositoryMock,
            $roomRepositoryMock,
            $bookBuilderMock,
            $reservationRepositoryMock,
        );

        $room = new Room();
        $room->setVacancy(true);

        $bookingService->checkBookedRoom($room);
        $this->assertTrue(true);
    }

    public function testShouldThrowExceptionIfRoomExistsAndNotBeVacancy(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $reservationRepositoryMock = $this->createMock(ReservationRepository::class);
        $roomRepositoryMock = $this->createMock(RoomRepository::class);
        $bookBuilderMock = $this->createMock(BookingBuilder::class);

        $bookingService = new BookingService(
            $userRepositoryMock,
            $roomRepositoryMock,
            $bookBuilderMock,
            $reservationRepositoryMock,
        );

        $room = new Room();
        $room->setVacancy(false);

        $this->expectException(RoomAlreadyBooked::class);
        $bookingService->checkBookedRoom($room);
    }

    /**
     * @throws \Exception
     */
    public function testShouldThrowExceptionIfStartDateIsPastThanNow(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $reservationRepositoryMock = $this->createMock(ReservationRepository::class);
        $roomRepositoryMock = $this->createMock(RoomRepository::class);
        $bookBuilderMock = $this->createMock(BookingBuilder::class);

        $reservationDto = new ReservationDTO();
        $reservationDto->setStartDate(Carbon::now()->hour(-2));

        $bookingService = new BookingService(
            $userRepositoryMock,
            $roomRepositoryMock,
            $bookBuilderMock,
            $reservationRepositoryMock,
        );

        $this->expectException(DateIsPastException::class);

        $bookingService->isPastDate($reservationDto->getStartDate());
    }
}
