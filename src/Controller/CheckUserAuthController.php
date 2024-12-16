<?php

namespace App\Controller;

use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckUserAuthController extends AbstractController
{

    #[Route('/check-user-auth', name: 'api_check_user_auth')]
    public function checkUserAuth(): JsonResponse
    {
        return new JsonResponse(
            "",
            Response::HTTP_OK
        );
    }
}