<?php

declare(strict_types=1);

namespace BabaissaNdiaye\LaravelResponseHandler\Services;

use BabaissaNdiaye\LaravelResponseHandler\Exceptions\AlreadyExistsException;
use BabaissaNdiaye\LaravelResponseHandler\Exceptions\BadRequestException;
use BabaissaNdiaye\LaravelResponseHandler\Exceptions\ExceptionInterface;
use BabaissaNdiaye\LaravelResponseHandler\Exceptions\NotFoundException;
use BabaissaNdiaye\LaravelResponseHandler\Exceptions\ServerErrorException;
use BabaissaNdiaye\LaravelResponseHandler\Exceptions\UnauthorizedException;
use BabaissaNdiaye\LaravelResponseHandler\Exceptions\UnprocessableEntityException;

class ExceptionFactory
{
    public function badRequest(string $message = ''): ExceptionInterface
    {
        return new BadRequestException($message);
    }

    public function notFound(string $message = ''): ExceptionInterface
    {
        return new NotFoundException($message);
    }

    public function unauthorized(string $message = ''): ExceptionInterface
    {
        return new UnauthorizedException($message);
    }

    public function alreadyExists(string $message = ''): ExceptionInterface
    {
        return new AlreadyExistsException($message);
    }

    public function serverError(string $message = ''): ExceptionInterface
    {
        return new ServerErrorException($message);
    }

    public function unprocessableEntity(string $message = ''): ExceptionInterface
    {
        return new UnprocessableEntityException($message);
    }
}