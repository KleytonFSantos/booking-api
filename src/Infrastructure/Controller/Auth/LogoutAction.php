<?php

namespace App\Infrastructure\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LogoutAction extends AbstractController
{
    public function __construct(
    ) {
    }

    #[Route('/logout', name: 'api_logout')]
    public function __invoke(Request $request): JsonResponse
    {
        //        $token = str_replace(['Bearer', ' '], '', $request->headers->get('Authorization'));
        return new JsonResponse(['message' => 'Logout successful']);
    }
}
