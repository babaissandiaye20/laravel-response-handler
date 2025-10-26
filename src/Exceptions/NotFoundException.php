<?php

declare(strict_types=1);

namespace BabaissaNdiaye\LaravelResponseHandler\Exceptions;

class NotFoundException extends BaseRuntimeException
{
    public function __construct(string $message = '', ?\Throwable $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }

    protected function getDefaultMessage(): string
    {
        return 'Ressource non trouvée';
    }
}