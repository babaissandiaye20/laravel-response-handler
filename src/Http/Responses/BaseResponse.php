<?php

declare(strict_types=1);

namespace BabaissaNdiaye\LaravelResponseHandler\Http\Responses;

use Illuminate\Http\JsonResponse;

abstract class BaseResponse implements ResponseInterface
{
    protected int $statusCode;
    protected string $message;
    protected mixed $data;

    public function __construct(string $message = '', mixed $data = null, int $statusCode = 200)
    {
        $this->statusCode = $statusCode;
        $this->message = $message ?: $this->getDefaultMessage();
        $this->data = $data;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function toJsonResponse(): JsonResponse
    {
        $data = $this->getData();

        if (is_object($data) && isset($data->password)) {
            unset($data->password);
        } elseif (is_array($data)) {
            $data = $this->hidePasswordInArray($data);
        }

        return response()->json([
            'success' => true,
            'status_code' => $this->getStatusCode(),
            'message' => $this->getMessage(),
            'data' => $data
        ], $this->getStatusCode());
    }

    protected function hidePasswordInArray(array $data): array
    {
        if (isset($data['password'])) {
            unset($data['password']);
        }

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->hidePasswordInArray($value);
            } elseif (is_object($value) && isset($value->password)) {
                unset($value->password);
            }
        }

        return $data;
    }

    abstract protected function getDefaultMessage(): string;
}