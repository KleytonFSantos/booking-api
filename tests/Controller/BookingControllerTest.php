<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BookingControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();

        static::getContainer()->get('doctrine')->getConnection()->beginTransaction();
    }

    /**
     * @throws \Exception
     */
    protected function tearDown(): void
    {
        static::getContainer()->get('doctrine')->getConnection()->rollBack();
    }

    /**
     * @throws \Exception
     */
    public function testCreateBooking()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('akatsukipb123@gmail.com');

        $this->client->loginUser($testUser);

        $this->client->request(
            'POST',
            '/booking',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"startDate": "2024-01-01", "endDate": "2024-01-05", "room": 2}'
        );

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Booking complete successfully', json_decode($this->client->getResponse()->getContent(), true)['message']);
    }
}