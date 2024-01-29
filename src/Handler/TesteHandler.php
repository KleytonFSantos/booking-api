<?php

namespace App\Handler;

use App\Teste\TesteQueue;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class TesteHandler
{
    public function __invoke(TesteQueue $message): TesteQueue
    {
        return $message;
    }
}
