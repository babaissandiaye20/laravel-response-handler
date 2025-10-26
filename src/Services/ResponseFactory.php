<?php

declare(strict_types=1);

namespace BabaissaNdiaye\LaravelResponseHandler\Services;

use BabaissaNdiaye\LaravelResponseHandler\Http\Responses\CreateResponse;
use BabaissaNdiaye\LaravelResponseHandler\Http\Responses\GetResponse;
use BabaissaNdiaye\LaravelResponseHandler\Http\Responses\SuccessResponse;
use Illuminate\Http\JsonResponse;

class ResponseFactory
{
    public function success(mixed $data = null, string $message = ''): JsonResponse
    {
        return (new SuccessResponse($message, $data))->toJsonResponse();
    }

    public function created(mixed $data = null, string $message = ''): JsonResponse
    {
        return (new CreateResponse($message, $data))->toJsonResponse();
    }

    public function retrieved(mixed $data = null, string $message = ''): JsonResponse
    {
        return (new GetResponse($message, $data))->toJsonResponse();
    }
}