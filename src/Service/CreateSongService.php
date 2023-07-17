<?php

namespace App\Service;

use App\Entity\Song;
use App\Repository\SongRepository;
use App\Validation\AddSongValidation;
use Exception;
use getID3;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class CreateSongService
{

    const FILE_PATH_COVER = 'uploads/cover/';
    const FILE_PATH_SONG = 'uploads/song/';

    public function __construct(
        private readonly UploadCoverFileService $uploadCoverFileService,
        private readonly UploadSongFileService $uploadSongFileService,
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
        }
    }


    public function getSongDuration(?string $songFile): ?string
    {
        if (!$songFile) {
            return null;
        }

        $getID3 = new getID3();
        $fileInfo = $getID3->analyze($songFile);
        $durationInSeconds = $fileInfo['playtime_seconds'];
        $minutes = floor($durationInSeconds / 60);
        $durationInSeconds %= 60;

        return sprintf('%d:%02d', $minutes, $durationInSeconds);
    }

    /**
     * @throws Exception
     */
    public function makeCoverUploadPath(?UploadedFile $coverFile): ?string
    {
        if (!$coverFile) {
            return null;
        }

        return self::FILE_PATH_COVER
            . $this->uploadCoverFileService->uploadCover($coverFile);
    }

    /**
     * @throws Exception
     */
    public function makeSongFileUploadPath($songFile): ?string
    {
        if ($songFile) {
            return self::FILE_PATH_SONG
                . $this->uploadSongFileService->uploadSong($songFile);
        }

        return null;
    }

    /**
     * @throws Exception
     */
    public function createSongArray(
        array $input,
        mixed $coverFile,
        mixed $songFile
    ): array {
        $input['cover'] = $this->makeCoverUploadPath($coverFile);
        $input['song'] = $this->makeSongFileUploadPath($songFile);
        $input['duration'] = $this->getSongDuration($input['song']);

        return $input;
    }
}