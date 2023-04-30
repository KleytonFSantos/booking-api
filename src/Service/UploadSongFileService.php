<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UploadSongFileService
{
    public function __construct(
        private readonly ParameterBagInterface  $params,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function uploadSong($songFile): string
    {
        $originalFilename = pathinfo($songFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename.'-'.uniqid().'.'.$songFile->guessExtension();
        try {
            $songFile->move(
                $this->params->get('upload_song_destination'),
                $newFilename
            );

            return $newFilename;
        } catch (FileException) {
            throw new Exception('Error uploading avatar file.');
        }
    }
}