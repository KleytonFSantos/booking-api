<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UploadAvatarFileService
{
    public function __construct(
        private readonly ParameterBagInterface  $params,
    )
    {
    }
    /**
     * @throws Exception
     */
    public function uploadAvatarImage($avatarFile): string
    {
        $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename.'-'.uniqid().'.'.$avatarFile->guessExtension();

        try {
            $avatarFile->move(
                $this->params->get('upload_destination'),
                $newFilename
            );

            return $newFilename;
        } catch (FileException) {
            throw new Exception('Error uploading avatar file.');
        }
    }
}