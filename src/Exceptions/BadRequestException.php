<?php

declare(strict_types=1);

namespace BabaissaNdiaye\LaravelResponseHandler\Exceptions;

class BadRequestException extends BaseRuntimeException
{
    public function __construct(string $message = '', ?\Throwable $previous = null)
    {
        parent::__construct($message, 400, $previous);
    }

    protected function getDefaultMessage(): string
    {
        return 'Requête invalide';
    }
}