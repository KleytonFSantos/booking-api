<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use DateInterval;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BookingControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();

        static::getContainer()->get('doctrine')->getConnection()->beginTransaction();
    }

    /**
     * @throws Exception
     */
    protected function tearDown(): void
    {
        static::getContainer()->get('doctrine')->getConnection()->rollBack();
    }

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

    /**
     * @throws Exception
     */
    protected function loginUser(): void
    {
        $this->client->loginUser($this->createUser());
    }

    /**
     * @throws Exception
     */
    protected function createUser(): User
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        return $userRepository->findOneByEmail('akatsukipb123@gmail.com');
    }

    protected function generateReservationContent(): string
    {
        $date = new DateTime();
        $startDate = $date->add(DateInterval::createFromDateString('+1 day'));
        $endDate = $date->add(DateInterval::createFromDateString('+5 days'));

        return json_encode([
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'room' => 2
        ]);
    }

}