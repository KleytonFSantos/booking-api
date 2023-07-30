<?php

namespace App\Validation;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationResponseErrors
{
    public function __construct(
        private readonly ValidatorInterface $validation
    ) {
    }

    public function validate(mixed $data): void
    {
        $errors = $this->validation->validate($data);
        $messages = ['message' => 'validation_failed', 'errors' => []];

        /* @var \Symfony\Component\Validator\ConstraintViolation */
        foreach ($errors as $message) {
            $messages['errors'][] = [
                'property' => $message->getPropertyPath(),
                'value' => $message->getInvalidValue(),
                'message' => $message->getMessage(),
            ];
        }

        if (count($messages['errors']) > 0) {
            $response = new JsonResponse(
                $messages,
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
            $response->send();

            exit;
        }
    }
}
