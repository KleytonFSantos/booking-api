<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadCoverFileService
{
    public function __construct(
        private readonly ParameterBagInterface  $params,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function uploadCover(?UploadedFile $coverFile): string
    {
        $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);
        $formattedFilename = str_replace(' ', '-', $originalFilename);
        $newFilename = $formattedFilename.'-'.uniqid().'.'.$coverFile->guessExtension();
        $validExtensions = ['png', 'jpg', 'jpeg'];

        if(!in_array($coverFile->guessExtension(), $validExtensions)) {
            throw new \Exception('Invalid image file detected', 500);
        }

        try {
            $coverFile->move(
                $this->params->get('upload_cover_destination'),
                $newFilename
            );

            return $newFilename;
        } catch (FileException) {
            throw new Exception('Error uploading avatar file.');
        }
    }
}