<?php

namespace App\Domain\Exception;

use Symfony\Component\HttpFoundation\Response;

class PaymentNotFound extends \Exception
{
    public function __construct(protected $message)
    {
        parent::__construct(
            $this->message,
            Response::HTTP_NOT_FOUND
        );
    }
}
