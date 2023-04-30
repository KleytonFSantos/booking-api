<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use App\Validation\UserProfileValidation;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class UserProfileService
{
    public function __construct(
        protected UserProfileRepository         $userProfileRepository,
        protected UserRepository                $userRepository,
        protected EntityManagerInterface        $entityManager,
        private readonly UserProfileValidation  $userProfileValidation

    )
    {
    }

    /**
     * @throws Exception
     */
    public function updateOrCreate(array $data, UserInterface $user, ?string $avatarUploaded): void
    {
        /** @var UserProfile $userExists */
        $userExists = $this->userProfileRepository->findOneBy(['user' => $user]);

        try {
            if ($userExists) {
                $this->extracted($userExists, $data, $avatarUploaded, $user);

                /**@var User $userData*/
                $userData = $userExists->getUser();

                $userData->setFirstName($data['first_name']);
                $userData->setLastName($data['last_name']);

                $this->userProfileRepository->save($userExists, true);
            } else {
                $userProfile = new UserProfile();

                $this->extracted($userProfile, $data, $avatarUploaded, $user);

                $this->userProfileRepository->save($userProfile, true);
            }
        } catch (ValidationFailedException $e) {
           $this->userProfileValidation->getValidationViolations($e->getViolations());
        } catch (Exception $e) {
            throw  new Exception($e->getMessage());
        }
    }

    /**
     * @param UserProfile $userExists
     * @param array $data
     * @param ?string $avatarUploaded
     * @param UserInterface $user
     * @return void
     */
    public function extracted(UserProfile $userExists, array $data, ?string $avatarUploaded, UserInterface $user): void
    {
        $userExists->setCity($data['city']);
        $userExists->setState($data['state']);
        $userExists->setAvatar($avatarUploaded);
        $userExists->setDescription($data['description']);
        $userExists->setUser($user);
        $violations = $this->userProfileValidation->validateUserProfile($userExists);

        if ($violations) {
            $this->userProfileValidation->getValidationViolations($violations);
        }
    }

}