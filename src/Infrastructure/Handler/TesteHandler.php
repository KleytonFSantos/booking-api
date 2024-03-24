<?php

namespace App\Infrastructure\Handler;

use App\Infrastructure\Teste\TesteQueue;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class TesteHandler
{
    public function __invoke(TesteQueue $message): TesteQueue
    {
        return $message;
    }
}
