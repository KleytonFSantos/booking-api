<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    public function __construct(
    ) {
    }

    #[Route('/logout', name: 'api_logout')]
    public function logout(Request $request): JsonResponse
    {
        //        $token = str_replace(['Bearer', ' '], '', $request->headers->get('Authorization'));
        //        dump($token);
        //        die();

        return new JsonResponse(['message' => 'Logout successful']);
    }
}
