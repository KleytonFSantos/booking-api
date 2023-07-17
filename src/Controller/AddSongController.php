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

    public function __construct(
        private readonly CreateSongService $createSongService,
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
            $coverFile = $request->files->get('cover');
            $songFile = $request->files->get('song');
            $input = $request->request->all();

            $song = $this->createSongService->createSongArray($input, $coverFile, $songFile);

            $this->createSongService->create($song, $user);

            return new JsonResponse(
                [
                    'message' => 'Song created successfully!',
                ],
                200
            );
        } catch (ValidationFailedException $e) {
            $errors = $this->addSongValidation->formattedJsonValidationErrors($e);
            return new JsonResponse(['errors' => $errors], 422);
        } catch (Exception $e) {
            dump($e->getMessage(), $e->getTraceAsString());
            return new JsonResponse($e, 500);
        }
    }
}
