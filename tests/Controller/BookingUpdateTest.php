<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;

class BookingUpdateTest extends ApiTestCase
{
    /**
     * @throws \Exception
     */
    public function testUpdateBooking()
    {
        $this->loginUser();
        $reservation = $this->createReservation();

        $this->client->request(
            'PUT',
            '/booking/'.$reservation->getId(),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $this->generateReservationUpdateContent()
        );

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
