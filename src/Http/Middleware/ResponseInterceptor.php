<?php

declare(strict_types=1);

namespace BabaissaNdiaye\LaravelResponseHandler\Http\Middleware;

use BabaissaNdiaye\LaravelResponseHandler\Services\ResponseFactory;
use BabaissaNdiaye\LaravelResponseHandler\Services\ExceptionFactory;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseInterceptor
{
    public function __construct(
        protected ResponseFactory $responseFactory,
        protected ExceptionFactory $exceptionFactory
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            return $this->formatResponse($response);
        }

        return $response;
    }

    protected function formatResponse(JsonResponse $response): JsonResponse
    {
        $statusCode = $response->getStatusCode();
        $originalData = json_decode($response->getContent(), true);

        if (isset($originalData['success']) && $originalData['success'] === false) {
            return $response;
        }

        $formattedResponse = match ($statusCode) {
            200 => $this->responseFactory->retrieved(
                $originalData['data'] ?? $originalData,
                $originalData['message'] ?? ''
            ),
            201 => $this->responseFactory->created(
                $originalData['data'] ?? $originalData,
                $originalData['message'] ?? ''
            ),
            422 => $this->createErrorResponse('UnprocessableEntity', $originalData['message'] ?? ''),
            400 => $this->createErrorResponse('BadRequest', $originalData['message'] ?? ''),
            401 => $this->createErrorResponse('Unauthorized', $originalData['message'] ?? ''),
            404 => $this->createErrorResponse('NotFound', $originalData['message'] ?? ''),
            409 => $this->createErrorResponse('AlreadyExists', $originalData['message'] ?? ''),
            500 => $this->createErrorResponse('ServerError', $originalData['message'] ?? ''),
            default => null
        };

        return $formattedResponse ?: $response;
    }

    protected function createErrorResponse(string $exceptionType, string $message): JsonResponse
    {
        $exception = match ($exceptionType) {
            'BadRequest' => $this->exceptionFactory->badRequest($message),
            'Unauthorized' => $this->exceptionFactory->unauthorized($message),
            'NotFound' => $this->exceptionFactory->notFound($message),
            'AlreadyExists' => $this->exceptionFactory->alreadyExists($message),
            'UnprocessableEntity' => $this->exceptionFactory->unprocessableEntity($message),
            'ServerError' => $this->exceptionFactory->serverError($message),
            default => $this->exceptionFactory->serverError($message)
        };

        return response()->json([
            'success' => false,
            'error' => [
                'message' => $exception->getErrorMessage(),
                'status_code' => $exception->getStatusCode()
            ]
        ], $exception->getStatusCode());
    }
}