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
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Process\Process;

class ApiTestCase extends WebTestCase
{
    public KernelBrowser $client;

    /**
     * @throws Exception
     */
    #[\Override]
    protected function setUp(): void
    {
        $this->client = static::createClient();

        if ($this->hasNoRooms()) {
            $this->runLoadFixtures();
        }

        if ($this->hasNoReservation()) {
            $this->createReservation();
        }

        static::getContainer()->get('doctrine')->getConnection()->beginTransaction();
    }

    /**
     * @throws Exception
     */
    #[\Override]
    protected function tearDown(): void
    {
        static::getContainer()->get('doctrine')->getConnection()->rollBack();
        static::ensureKernelShutdown();
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

    /**
     * @throws Exception
     */
    private function hasNoRooms(): bool
    {
        $roomRepository = $this->getContainer()->get(RoomRepository::class);

        return empty($roomRepository->findBy(['roomNumber' => range(9, 9)]));
    }

    /**
     * @throws Exception
     */
    private function getRoom(): Room
    {
        $roomRepository = static::getContainer()->get(RoomRepository::class);
        return $roomRepository->findOneBy(['roomNumber' => 1]);
    }

    /**
     * @throws Exception
     */
    protected function hasNoReservation(): bool
    {
        $reservationRepository = $this->getContainer()->get(ReservationRepository::class);

        return empty($reservationRepository->findBy(['status' => ReservationStatusEnum::RESERVED->value]));
    }

    /**
     * @throws Exception
     */
    protected function createReservation(): Reservation
    {
        $reservationRepository = static::getContainer()->get(ReservationRepository::class);

        $user = $this->createUser();

        $room = $this->getRoom();

        $date = new DateTime();
        $startDate = $date->add(DateInterval::createFromDateString('+1 day'));
        $endDate = $date->add(DateInterval::createFromDateString('+5 days'));

        $reservation = new Reservation();
        $reservation->setUser($user);
        $reservation->setRoom($room);
        $reservation->setPrice($room->getPrice());
        $reservation->setStartDate($startDate);
        $reservation->setEndDate($endDate);
        $reservation->setStatus(ReservationStatusEnum::RESERVED->value);

        $reservationRepository->save($reservation);

        return $reservation;
    }

    public function generateReservationContent(): string
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

    protected function runLoadFixtures(): void
    {
        $command = 'php bin/console hautelook:fixtures:load --no-interaction';

        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException('Falha ao executar o comando de migração.');
        }
    }
}