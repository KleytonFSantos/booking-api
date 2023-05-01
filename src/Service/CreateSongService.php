<?php

namespace App\Service;

use App\Entity\Song;
use App\Repository\SongRepository;
use App\Validation\AddSongValidation;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class CreateSongService
{
    public function __construct(
        private readonly SongRepository $songRepository,
        private readonly AddSongValidation $addSongValidation
    )
    {
    }

    /**
     * @throws Exception
     */
    public function create(array $song, UserInterface $user): void
    {
        try {
            $newSong = new Song();
            $newSong->setTitle($song['title']);
            $newSong->setArtist($song['artist']);
            $newSong->setCover($song['cover'] ?? '');
            $newSong->setSong($song['song']);
            $newSong->setDuration($song['duration'] ?? '');
            $newSong->setUser($user);

            $violations = $this->addSongValidation->validateUserProfile($newSong);

            if ($violations || $newSong->getSong() === 'undefined') {
                $this->addSongValidation->getValidationViolations($violations);
            }
            $this->songRepository->save($newSong, true);
        } catch (ValidationFailedException $e) {
            $this->addSongValidation->getValidationViolations($e->getViolations());
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}