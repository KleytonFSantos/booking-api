<?php

namespace App\Handler;

use App\Teste\TesteQueue;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class TesteHandler
{

    public function __invoke(TesteQueue $message): TesteQueue
    {
        return $message;
    }
}