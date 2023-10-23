<?php

namespace App\Tests;

use App\Entity\Reservation;
use App\Entity\Room;
use App\Enum\ReservationStatusEnum;
use App\Exception\ReservationNotFound;
use App\Factory\BookingBuilder;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use App\Service\BookingService;
use PHPUnit\Framework\TestCase;

class FinishedBookingTest extends TestCase
{
    /**
     * @throws ReservationNotFound
     */
    public function testFinishReservation()
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $reservationRepositoryMock = $this->createMock(ReservationRepository::class);
        $roomRepositoryMock = $this->createMock(RoomRepository::class);
        $bookBuilderMock = $this->createMock(BookingBuilder::class);

        $reservation = new Reservation();

        $room = new Room();
        $reservation->setRoom($room);

        $reservationRepositoryMock->expects($this->once())
            ->method('find')
            ->willReturn($reservation);

        $roomRepositoryMock->expects($this->once())
            ->method('find')
            ->willReturn($room);

        $bookingService = new BookingService(
            $userRepositoryMock,
            $roomRepositoryMock,
            $bookBuilderMock,
            $reservationRepositoryMock,
        );

        $bookingService->finished($reservation->getId());

        $this->assertSame(ReservationStatusEnum::FINISHED->value, $reservation->getStatus());
        $this->assertTrue($room->isVacancy());
    }
}
