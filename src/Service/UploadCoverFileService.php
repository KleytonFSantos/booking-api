<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
    public function uploadCover($coverFile): string
    {
        $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename.'-'.uniqid().'.'.$coverFile->guessExtension();
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