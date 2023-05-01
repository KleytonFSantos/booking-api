<?php

namespace App\Controller;

use App\Service\CreateSongService;
use App\Service\UploadCoverFileService;
use App\Service\UploadSongFileService;
use App\Validation\AddSongValidation;
use Exception;
use getID3;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class AddSongController extends AbstractController
{
    const FILE_PATH_COVER = 'uploads/cover/';
    const FILE_PATH_SONG = 'uploads/song/';

    public function __construct(
        private readonly CreateSongService $createSongService,
        private readonly UploadCoverFileService $uploadCoverFileService,
        private readonly UploadSongFileService $uploadSongFileService,
        private readonly AddSongValidation $addSongValidation
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route('/add-song', name: 'app_add_song', methods: 'POST')]
    public function index(Request $request, UserInterface $user): JsonResponse
    {
        try {
            $coverFile = $request->files->get('coverImage');
            $songFile = $request->files->get('song');

            $input = $request->request->all();
            
            if ($coverFile) {
                $uploadCover = $this->uploadCoverFileService->uploadCover($coverFile);
                $coverUploaded = self::FILE_PATH_COVER . $uploadCover;
                $input['cover'] = $coverUploaded;
            }

            if ($songFile) {
                $uploadSong = $this->uploadSongFileService->uploadSong($songFile);
                $songUploaded = self::FILE_PATH_SONG . $uploadSong;
                $input['song'] = $songUploaded;
                $input['duration'] = $this->getSongDuration($songUploaded);
            }

            $this->createSongService->create($input, $user);

            return new JsonResponse([
                'message' => 'Song created successfully!',
            ],200);
        } catch (ValidationFailedException $e) {
            $errors = $this->addSongValidation->formattedJsonValidationErrors($e);
            return new JsonResponse(['errors' => $errors], 422);
        } catch (Exception $e) {
            dump($e->getMessage(), $e->getTraceAsString());
            return new JsonResponse($e, 500);
        }
    }

    public function getSongDuration($songFile): string
    {
        $getID3 = new getID3();
        $fileInfo = $getID3->analyze($songFile);
        $durationInSeconds = $fileInfo['playtime_seconds'];
        $minutes = floor($durationInSeconds / 60);
        $durationInSeconds %= 60;
        return sprintf('%d:%02d', $minutes, $durationInSeconds);
    }
}
