<?php

namespace App\Validation;

use App\Entity\UserProfile;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserProfileValidation
{
    public function __construct(
        private readonly ValidatorInterface     $validator,
    )
    {
    }
    public function getValidationViolations($violations): void
    {
        throw new ValidationFailedException(
            $violations[0]->getMessage(),
            $violations
        );
    }

    public function validateUserProfile(UserProfile $userProfile): ?ConstraintViolationListInterface
    {
        $errors = $this->validator->validate($userProfile);
        if ($errors->count() > 0) {
            return $errors;
        }

        return null;
    }

    public function formattedJsonValidationErrors($violations): array
    {
        $errors = [];
        foreach ($violations->getViolations() as $violation) {
            $field = preg_replace('/\[|\]/', '', $violation->getPropertyPath());
            $errors[$field] = $violation->getMessage();
        }

        return $errors;
    }
}