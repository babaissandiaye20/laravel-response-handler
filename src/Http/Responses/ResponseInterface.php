<?php

declare(strict_types=1);

namespace BabaissaNdiaye\LaravelResponseHandler\Http\Responses;

use Illuminate\Http\JsonResponse;

interface ResponseInterface
{
    public function getStatusCode(): int;
    public function getMessage(): string;
    public function getData(): mixed;
    public function toJsonResponse(): JsonResponse;
}