<?php

namespace App\Controller;

use App\Entity\UserProfile;
use App\Repository\UserProfileRepository;
use App\Service\UploadAvatarFileService;
use App\Service\UserProfileService;
use App\Validation\UserProfileValidation;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ProfileController extends AbstractController
{
    const FILE_PATH =  'uploads/avatar/';
    public function __construct(
        private readonly UserProfileRepository   $userProfileRepository,
        private readonly UserProfileValidation   $userProfileValidation,
        private readonly UploadAvatarFileService $uploadAvatarFileService
    )
    {
    }

    #[Route('/get-user', name: 'user_authenticated_data', methods: 'GET')]
    public function getUserAuthenticatedData(UserInterface $user): JsonResponse
    {
        /** @var UserProfile $userProfile */
        $userProfile = $this->userProfileRepository->findOneBy(['user' => $user]);

        return new JsonResponse([
            'description' => $userProfile->getDescription(),
            'avatar' => $userProfile->getAvatar(),
            'city' => $userProfile->getCity(),
            'state' => $userProfile->getState(),
            'user' => $userProfile->getUser()
        ], 200);
    }
    #[Route('/edit-profile', name: 'edit-profile', methods: 'POST')]
    public function editProfile(
        Request $request,
        UserInterface $user,
        UserProfileService $userProfileService,
    ): JsonResponse {
        try {
            $avatarFile = $request->files->get('avatar');
            $input = $request->request->all();

            $avatarUploaded = null;
            if ($avatarFile) {
                $uploadAvatar = $this->uploadAvatarFileService->uploadAvatarImage($avatarFile);
                $avatarUploaded = self::FILE_PATH . $uploadAvatar;
            }

            $input['avatar'] = $avatarUploaded;
            $userProfileService->updateOrCreate($input, $user, $avatarUploaded);
            return new JsonResponse('Profile updated successfully', 200);
        } catch (ValidationFailedException $e) {
            $errors = $this->userProfileValidation->formattedJsonValidationErrors($e);
            return new JsonResponse(['errors' => $errors], 422);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), 500);
        }
    }
}