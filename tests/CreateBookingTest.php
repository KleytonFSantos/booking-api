<?php

namespace App\Tests;

use App\Entity\Room;
use App\Exception\RoomAlreadyBooked;
use App\Service\BookingService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CreateBookingTest extends KernelTestCase
{
    public function testRoomExistsAndBeVacancy(): void
    {
        self::bootKernel();
        $container = static::getContainer();
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
}
