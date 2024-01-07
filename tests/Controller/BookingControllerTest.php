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
        $userByEmail = $userRepository->findOneBy(['email' => 'akatsukipb123@gmail.com']);

        if (empty($userByEmail)) {
            $newUser = new User();
            $newUser->setEmail('akatsukipb123@gmail.com');
            $newUser->setName('kleyton');
            $newUser->setPassword('12345');
            $userRepository->save($newUser);
            return $newUser;
        }

        return $userByEmail;
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