<?php

namespace App\Tests;

use App\DTO\ReservationDTO;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\User;
use App\Exception\DateIsPasteException;
use App\Exception\RoomAlreadyBooked;
use App\Factory\BookingBuilder;
use App\Service\BookingService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CreateBookingTest extends KernelTestCase
{
    public function testRoomExistsAndBeVacancy(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var BookingService $bookingService */
        $bookingService = $container->get(BookingService::class);

        $room = new Room();
        $room->setVacancy(true);
        $room->setRoomNumber(2);
        $room->setPrice(100);

        try {
            $bookingService->checkBookedRoom($room);

            $this->assertTrue(true);
        } catch (RoomAlreadyBooked $exception) {
            $this->fail('An unexpected exception was thrown: '.$exception->getMessage());
        }
    }

    public function testShouldThrowExceptionIfRoomExistsAndNotBeVacancy(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        /** @var BookingService $bookingService */
        $bookingService = $container->get(BookingService::class);

        $room = new Room();
        $room->setVacancy(false);
        $room->setRoomNumber(2);
        $room->setPrice(100);

        $this->expectException(RoomAlreadyBooked::class);

        $bookingService->checkBookedRoom($room);
    }

    /**
     * @throws \Exception
     */
    public function testShouldThrowExceptionIfStartDateIsPateThanNow(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $room = new Room();
        $room->setVacancy(false);
        $room->setRoomNumber(2);
        $room->setPrice(100);

        $reservationDto = new ReservationDTO();
        $reservationDto->setStartDate('23-08-24');
        $reservationDto->setRoom($room->getId());
        $reservationDto->setEndDate('23-08-30');

        /** @var BookingService $bookingService */
        $bookingService = $container->get(BookingService::class);

        $this->expectException(DateIsPasteException::class);

        $bookingService->isPastDate($reservationDto->getStartDate());
    }
}
