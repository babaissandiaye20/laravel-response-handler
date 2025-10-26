<?php

namespace BabaissaNdiaye\LaravelResponseHandler\Tests\Feature;

use BabaissaNdiaye\LaravelResponseHandler\Services\ExceptionFactory;
use BabaissaNdiaye\LaravelResponseHandler\Exceptions\NotFoundException;
use BabaissaNdiaye\LaravelResponseHandler\Exceptions\UnauthorizedException;
use BabaissaNdiaye\LaravelResponseHandler\Exceptions\BadRequestException;
use Orchestra\Testbench\TestCase;

class ExceptionFactoryTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            \BabaissaNdiaye\LaravelResponseHandler\ResponseHandlerServiceProvider::class,
        ];
    }

    public function test_exception_factory_can_be_instantiated(): void
    {
        $factory = $this->app->make(ExceptionFactory::class);
        $this->assertInstanceOf(ExceptionFactory::class, $factory);
    }

    public function test_not_found_exception_creation(): void
    {
        $factory = $this->app->make(ExceptionFactory::class);
        $exception = $factory->notFound('Resource not found');
        
        $this->assertInstanceOf(NotFoundException::class, $exception);
        $this->assertEquals('Resource not found', $exception->getErrorMessage());
        $this->assertEquals(404, $exception->getStatusCode());
    }

    public function test_unauthorized_exception_creation(): void
    {
        $factory = $this->app->make(ExceptionFactory::class);
        $exception = $factory->unauthorized('Access denied');
        
        $this->assertInstanceOf(UnauthorizedException::class, $exception);
        $this->assertEquals('Access denied', $exception->getErrorMessage());
        $this->assertEquals(401, $exception->getStatusCode());
    }

    public function test_bad_request_exception_creation(): void
    {
        $factory = $this->app->make(ExceptionFactory::class);
        $exception = $factory->badRequest('Invalid request');
        
        $this->assertInstanceOf(BadRequestException::class, $exception);
        $this->assertEquals('Invalid request', $exception->getErrorMessage());
        $this->assertEquals(400, $exception->getStatusCode());
    }
}