<?php

namespace BabaissaNdiaye\LaravelResponseHandler\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            \BabaissaNdiaye\LaravelResponseHandler\ResponseHandlerServiceProvider::class,
        ];
    }
}