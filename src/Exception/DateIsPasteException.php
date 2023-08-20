<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class DateIsPasteException extends \Exception
{
    public function __construct(
        protected $message
    ) {
        parent::__construct(
            $this->errorMessage(),
            Response::HTTP_BAD_REQUEST
        );
    }

    public function errorMessage(): string
    {
        return $this->message;
    }
}
