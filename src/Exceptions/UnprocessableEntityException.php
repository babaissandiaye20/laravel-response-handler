<?php

declare(strict_types=1);

namespace BabaissaNdiaye\LaravelResponseHandler\Exceptions;

class UnprocessableEntityException extends BaseRuntimeException
{
    public function __construct(string $message = '', ?\Throwable $previous = null)
    {
        parent::__construct($message, 422, $previous);
    }

    protected function getDefaultMessage(): string
    {
        return 'Les données fournies sont invalides';
    }
}