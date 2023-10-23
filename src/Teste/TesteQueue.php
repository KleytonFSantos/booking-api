<?php

namespace App\Teste;

class TesteQueue
{
    public function __construct(private readonly string $teste)
    {
    }

    public function getTeste(): string
    {
        return $this->teste;
    }
}