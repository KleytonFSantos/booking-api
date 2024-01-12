<?php

namespace App\Tests\Controller;


use Exception;
use Symfony\Component\HttpFoundation\Response;

class BookingByRoomControllerTest extends ApiTestCase
{

    /**
     * @throws Exception
     */
    public function testBookingByRoomController(): void
    {
        $this->loginUser();

        $this->client->request(
            'GET',
            '/booking/1',
            ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertCount(1, json_decode($this->client->getResponse()->getContent()));
    }
}