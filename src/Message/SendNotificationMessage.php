<?php

namespace App\Message;

class SendNotificationMessage
{
    public function __construct(private readonly string $text)
    {
    }

    public function getText(): string
    {
        return $this->text;
    }
}
