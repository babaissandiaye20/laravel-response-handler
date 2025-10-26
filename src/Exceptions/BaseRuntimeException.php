<?php

declare(strict_types=1);

namespace BabaissaNdiaye\LaravelResponseHandler\Exceptions;

use RuntimeException;

abstract class BaseRuntimeException extends RuntimeException implements ExceptionInterface
{
    protected int $statusCode;
    protected string $errorMessage;

    public function __construct(string $message = '', int $statusCode = 500, ?\Throwable $previous = null)
    {
        $this->statusCode = $statusCode;
        $this->errorMessage = $message ?: $this->getDefaultMessage();
        
        parent::__construct($this->errorMessage, $statusCode, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    abstract protected function getDefaultMessage(): string;
}