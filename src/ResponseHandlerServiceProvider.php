<?php

declare(strict_types=1);

namespace BabaissaNdiaye\LaravelResponseHandler;

use BabaissaNdiaye\LaravelResponseHandler\Services\ExceptionFactory;
use BabaissaNdiaye\LaravelResponseHandler\Services\ResponseFactory;
use Illuminate\Support\ServiceProvider;

class ResponseHandlerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ExceptionFactory::class, function (): ExceptionFactory {
            return new ExceptionFactory();
        });

        $this->app->singleton(ResponseFactory::class, function (): ResponseFactory {
            return new ResponseFactory();
        });
    }

    public function boot(): void
    {
        //
    }
}