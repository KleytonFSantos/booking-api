<?php

namespace App\Tests\Controller;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class BookingControllerTest extends ApiTestCase
{

    /**
     * @throws Exception
     */
    public function testCreateBooking()
    {
        $this->loginUser();

        $this->client->request(
            'POST',
            '/booking',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $this->generateReservationContent()
        );

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Booking complete successfully', json_decode($this->client->getResponse()->getContent(), true)['message']);
    }
}