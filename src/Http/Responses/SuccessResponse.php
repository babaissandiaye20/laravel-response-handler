<?php

declare(strict_types=1);

namespace BabaissaNdiaye\LaravelResponseHandler\Http\Responses;

class SuccessResponse extends BaseResponse
{
    public function __construct(string $message = '', mixed $data = null)
    {
        parent::__construct($message, $data, 200);
    }

    protected function getDefaultMessage(): string
    {
        return 'Opération réussie';
    }
}