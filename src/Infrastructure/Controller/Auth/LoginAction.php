<?php

namespace App\Infrastructure\Controller\Auth;

use App\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class LoginAction extends AbstractController
{
    #[Route('/login', name: 'api_login')]
    public function __invoke(#[CurrentUser] ?User $user): JsonResponse
    {
        if (!$user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }
        $token = 'token';

        return new JsonResponse([
                'user' => $user->getUserIdentifier(),
                'token' => $token,
            ]
        );
    }
}
