<?php

declare(strict_types=1);

namespace BabaissaNdiaye\LaravelResponseHandler\Exceptions;

class AlreadyExistsException extends BaseRuntimeException
{
    public function __construct(string $message = '', ?\Throwable $previous = null)
    {
        parent::__construct($message, 409, $previous);
    }

    protected function getDefaultMessage(): string
    {
        return 'La ressource existe déjà';
    }
}