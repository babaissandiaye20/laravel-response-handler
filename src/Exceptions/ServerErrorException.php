<?php

declare(strict_types=1);

namespace BabaissaNdiaye\LaravelResponseHandler\Exceptions;

class ServerErrorException extends BaseRuntimeException
{
    public function __construct(string $message = '', ?\Throwable $previous = null)
    {
        parent::__construct($message, 500, $previous);
    }

    protected function getDefaultMessage(): string
    {
        return 'Erreur interne du serveur';
    }
}