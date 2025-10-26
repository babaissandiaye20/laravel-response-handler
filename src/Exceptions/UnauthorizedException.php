<?php

declare(strict_types=1);

namespace BabaissaNdiaye\LaravelResponseHandler\Exceptions;

class UnauthorizedException extends BaseRuntimeException
{
    public function __construct(string $message = '', ?\Throwable $previous = null)
    {
        parent::__construct($message, 401, $previous);
    }

    protected function getDefaultMessage(): string
    {
        return 'Accès non autorisé';
    }
}