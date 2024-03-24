<?php

namespace App\Infrastructure\Message;

readonly class SendEmailToRemindReservationMessage
{
    public function __construct(
        private string $email,
        private string $username
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
