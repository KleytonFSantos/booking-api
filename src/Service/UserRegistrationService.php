<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserRegistrationService
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly ValidatorInterface          $validator,
    )
    {
    }

    public function hashPassword(string $plainTextPassword): string
    {
        $user = new User();
        return $this->passwordHasher->hashPassword(
            $user,
            $plainTextPassword
        );
    }

    public function validateUser(User $user): ?ConstraintViolationListInterface
    {
        $errors = $this->validator->validate($user);

        if ($errors->count() > 0) {
            return $errors;
        }

        return null;
    }
}