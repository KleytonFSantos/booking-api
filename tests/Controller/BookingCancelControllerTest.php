<?php

namespace App\Tests\Controller;

use App\Repository\ReservationRepository;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class BookingCancelControllerTest extends ApiTestCase
{
    /**
     * @throws Exception
     */
    public function testBookingCancelController(): void
    {
        $this->loginUser();
        $reservation = $this->createReservation();
        $reservationRepository = static::getContainer()->get(ReservationRepository::class);
        $reservationRepository->save($reservation);

        $this->client->request(
            'GET',
            "/cancel/{$reservation->getId()}",
            ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSame("Booking {$reservation->getId()} was canceled successfully", json_decode($this->client->getResponse()->getContent())->message);
    }
}