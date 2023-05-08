<?php

namespace App\Service;

use App\Enum\ValidAudioFileExtensions;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadSongFileService
{
    const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB

    public function __construct(
        private readonly ParameterBagInterface  $params,
        private readonly LoggerInterface $logger
    )
    {
    }

    /**
     * @throws Exception
     */
    public function uploadSong(UploadedFile $songFile): string
    {
        $originalFilename = pathinfo($songFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename.'-'.uniqid().'.'.$songFile->guessExtension();

        if($songFile->getSize() > self::MAX_FILE_SIZE) {
            throw new \Exception('The file is larger than 10mb', 400);
        }

        if (!in_array($songFile->guessExtension(), ValidAudioFileExtensions::values())) {
             throw new \Exception('Invalid audio file detected', 400);
        }

         try {
             $songFile->move(
                $this->params->get('upload_song_destination'),
                $newFilename
            );

            return $newFilename;
        } catch (FileException $e) {
             $this->logger->error($e->getTraceAsString());
             throw new Exception('Error uploading avatar file.');
        }
    }
}