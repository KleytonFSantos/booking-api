<?php

namespace App\Exception;

class ReservationNotFound extends \Exception
{
    public function __construct(
        protected $message,
        protected $code
    ) {
        parent::__construct(
            $this->message,
            $this->code
        );
    }
}
