<?php

namespace App\Infrastructure\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckUserAuthAction extends AbstractController
{

    #[Route('/check-user-auth', name: 'api_check_user_auth')]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(
            "",
            Response::HTTP_OK
        );
    }
}