<?php

declare(strict_types=1);

namespace BabaissaNdiaye\LaravelResponseHandler\Exceptions;

interface ExceptionInterface
{
    public function getStatusCode(): int;
    public function getErrorMessage(): string;
}