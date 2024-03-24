<?php

namespace App\Tests\UnitTests;

use App\Domain\DTO\ReservationDTO;
use App\Domain\Entity\Room;
use App\Domain\Exception\DateIsPastException;
use App\Domain\Exception\RoomAlreadyBooked;
use App\Domain\Factory\BookingBuilder;
use App\Domain\Repository\ReservationRepository;
use App\Domain\Repository\RoomRepository;
use App\Domain\Repository\UserRepository;
use App\Infrastructure\Service\BookingService;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CreateBookingTest extends KernelTestCase
{
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

        try {
            $bookingService->checkBookedRoom($room);

            $this->assertTrue(true);
        } catch (RoomAlreadyBooked $exception) {
            $this->fail('An unexpected exception was thrown: '.$exception->getMessage());
        }
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
        $reservationDto->setStartDate(Carbon::now()->hour(-2)->setTimezone('America/Sao_Paulo'));

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
