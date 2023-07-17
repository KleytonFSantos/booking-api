<?php

namespace App\Tests\WebTest;

use App\Entity\Song;
use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateSongRequestTest extends WebTestCase
{
    /**
     * @throws Exception
     */
    public function testCreateSongRequestFailsIfDontHaveSongFile(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
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

        $client->request('POST', '/add-song', [
            'song' => '',
            'title' => 'this-song-title',
            'artist' => 'this-artist',
            'cover' => 'this-song-cover',
        ]);

        $this->assertResponseIsUnprocessable();
        $this->assertJson(
            '{"errors":{"song":"This value should not be blank."}}',
            $client->getResponse()->getContent()
        );
    }
}