<?php

namespace BabaissaNdiaye\LaravelResponseHandler\Tests\Feature;

use BabaissaNdiaye\LaravelResponseHandler\Services\ResponseFactory;
use Orchestra\Testbench\TestCase;

class ResponseFactoryTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            \BabaissaNdiaye\LaravelResponseHandler\ResponseHandlerServiceProvider::class,
        ];
    }

    public function test_response_factory_can_be_instantiated(): void
    {
        $factory = $this->app->make(ResponseFactory::class);
        $this->assertInstanceOf(ResponseFactory::class, $factory);
    }

    public function test_success_response_format(): void
    {
        $factory = $this->app->make(ResponseFactory::class);
        $response = $factory->success(['test' => 'data'], 'Test message');
        
        $this->assertEquals(200, $response->getStatusCode());
        
        $content = json_decode($response->getContent(), true);
        $this->assertTrue($content['success']);
        $this->assertEquals(200, $content['status_code']);
        $this->assertEquals('Test message', $content['message']);
        $this->assertEquals(['test' => 'data'], $content['data']);
    }

    public function test_created_response_format(): void
    {
        $factory = $this->app->make(ResponseFactory::class);
        $response = $factory->created(['id' => 1], 'Resource created');
        
        $this->assertEquals(201, $response->getStatusCode());
        
        $content = json_decode($response->getContent(), true);
        $this->assertTrue($content['success']);
        $this->assertEquals(201, $content['status_code']);
        $this->assertEquals('Resource created', $content['message']);
        $this->assertEquals(['id' => 1], $content['data']);
    }
}