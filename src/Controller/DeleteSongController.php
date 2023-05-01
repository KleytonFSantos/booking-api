<?php

namespace App\Controller;

use App\Repository\SongRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteSongController extends AbstractController
{

    #[Route('/song/{song}', name: 'app_delete_song', methods: 'DELETE')]
    public function __invoke(
        int $song,
        SongRepository $songRepository,
        Filesystem $filesystem
    ): JsonResponse {
        try {
            $song = $songRepository->find($song);

            $coverPath = $song->getCover();
            $songPath = $song->getSong();

            $songRepository->remove($song, true);

            $filesystem->remove($coverPath);
            $filesystem->remove($songPath);

            return new JsonResponse('', Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), 500);
        }
    }
}
