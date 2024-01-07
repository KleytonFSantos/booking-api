<?php

namespace App\Tests\Controller;

use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\User;
use App\Enum\ReservationStatusEnum;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use DateInterval;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;

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