<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserRegistrationService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly SerializerInterface $serializer,
        private readonly UserRegistrationService $userRegistrationService
    ) {
    }

    #[Route('/register', name: 'app_registration', methods: 'POST')]
    public function register(Request $request): JsonResponse
    {
        try {
            /* @var $user User */
            $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');

            $validationErrors = $this->userRegistrationService->validateUser($user);

            if ($validationErrors) {
                return new JsonResponse(
                    [
                        'error' => (string) $validationErrors[0]->getMessage(),
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $hashedPassword = $this->userRegistrationService->hashPassword($user->getPassword());
            $user->setPassword($hashedPassword);

            $this->userRepository->save($user, true);

            return new JsonResponse(
                ['user' => $user->getUserIdentifier()],
                Response::HTTP_OK,
                [],
            );
        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse(
                [
                    'error' => 'This email is already registered.',
                    'type' => 'Unique Email Constraint'
                ],
                Response::HTTP_BAD_REQUEST,
                [],
            );
        }  catch (\Exception $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR,
                [],
            );
        }
    }
}
