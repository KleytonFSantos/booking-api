<?php

namespace App\Tests\WebTest;

use App\Entity\User;
use App\Repository\SongRepository;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetSongRequestTest extends WebTestCase
{
    /**
     * @throws Exception
     */
    public function testGetSongRequest(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $songRepository = static::getContainer()->get(SongRepository::class);

        $userTest = $userRepository->findOneBy(['email' => 'test@example.com']);

        if (!$userTest) {
            $newUser = new User();
            $newUser->setEmail('test@example.com');
            $newUser->setFirstName('test');
            $newUser->setLastName('lastName');
            $newUser->setPassword('123456478');
            $newUser->setPasswordConfirmation('123456478');

            $userTest = $newUser;
        }


        $client->loginUser($userTest);

        $client->request('GET', '40' . '/songs');

        $this->assertResponseIsSuccessful();
    }
}