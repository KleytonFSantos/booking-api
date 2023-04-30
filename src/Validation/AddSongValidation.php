<?php

namespace App\Validation;

use App\Entity\Song;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddSongValidation
{
    public function __construct(
        private readonly ValidatorInterface     $validator,
    )
    {
    }
    public function validateUserProfile(Song $song): ?ConstraintViolationListInterface
    {
        $errors = $this->validator->validate($song);
        if ($errors->count() > 0) {
            return $errors;
        }

        return null;
    }

    public function getValidationViolations(ConstraintViolationListInterface $violations)
    {
        throw new ValidationFailedException(
            $violations[0]->getMessage(),
            $violations
        );
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