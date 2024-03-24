<?php

namespace App\Tests\UnitTests;

use App\Domain\Entity\Reservation;
use App\Domain\Entity\Room;
use App\Domain\Enum\ReservationStatusEnum;
use App\Domain\Exception\ReservationNotFound;
use App\Domain\Factory\BookingBuilder;
use App\Domain\Repository\ReservationRepository;
use App\Domain\Repository\RoomRepository;
use App\Domain\Repository\UserRepository;
use App\Infrastructure\Service\BookingService;
use PHPUnit\Framework\TestCase;

class CancelBookingTest extends TestCase
{
    /**
     * @throws ReservationNotFound
     */
    public function testCancelReservation(): void
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

        $bookingService->cancel($reservation->getId());

        $this->assertSame(ReservationStatusEnum::CANCELED->value, $reservation->getStatus());
        $this->assertTrue($room->isVacancy());
    }

    public function testCancelNonExistentReservation(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $reservationRepositoryMock = $this->createMock(ReservationRepository::class);
        $roomRepositoryMock = $this->createMock(RoomRepository::class);
        $bookBuilderMock = $this->createMock(BookingBuilder::class);

        $reservationRepositoryMock->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $bookingService = new BookingService(
            $userRepositoryMock,
            $roomRepositoryMock,
            $bookBuilderMock,
            $reservationRepositoryMock,
        );

        $this->expectException(ReservationNotFound::class);

        $bookingService->cancel(1);
    }
}
