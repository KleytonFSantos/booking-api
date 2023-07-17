<?php

namespace App\Controller;

use App\Entity\Song;
use App\Exceptions\CreateNotFoundException;
use App\Repository\SongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GetSongsController extends AbstractController
{
    public function __construct(
        private readonly SongRepository $songRepository,
    )
    {
    }

    #[Route('/{user}/songs', name: 'app_get_songs', methods: 'GET')]
    public function __invoke(int $user, SerializerInterface $serializer): JsonResponse
    {
        try {
            $songs = $this->songRepository->findBy(['user' => $user]);

            if (!$songs) {
                throw $this->createNotFoundException('No songs founded!');
            }

            $data = $serializer->serialize($songs, 'json', ['groups' => 'song']);
            return new JsonResponse($data, 200, [], true);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(
                [
                    'message' => $e->getMessage(),
                ],
                $e->getStatusCode(),
            );
        }
    }
}
