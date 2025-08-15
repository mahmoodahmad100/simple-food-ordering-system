<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Traits\Response;
use Illuminate\Http\JsonResponse;

class ResponseTraitTest extends TestCase
{
    use Response;

    public function test_get_response_success()
    {
        $data = ['id' => 1, 'name' => 'Test'];
        $response = $this->getResponse($data, 200, 'Success message');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        $this->assertTrue($content['is_success']);
        $this->assertEquals(200, $content['status_code']);
        $this->assertEquals('Success message', $content['message']);
        $this->assertEquals($data, $content['data']);
    }

    public function test_get_response_error()
    {
        $errors = ['field' => 'error message'];
        $response = $this->getResponse($errors, 422, 'Error message');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(422, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        $this->assertFalse($content['is_success']);
        $this->assertEquals(422, $content['status_code']);
        $this->assertEquals('Error message', $content['message']);
        $this->assertEquals($errors, $content['errors']);
    }

    public function test_get_response_with_default_message()
    {
        $response = $this->getResponse([], 200);

        $content = json_decode($response->getContent(), true);
        $this->assertEquals('Success.', $content['message']);

        $response = $this->getResponse([], 400);

        $content = json_decode($response->getContent(), true);
        $this->assertEquals('Error.', $content['message']);
    }

    public function test_get_exception_response_without_reporting()
    {
        $exception = new \Exception('Test exception');
        
        $response = $this->getExceptionResponse($exception, false);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        $this->assertFalse($content['is_success']);
        $this->assertEquals(500, $content['status_code']);
    }

    public function test_get_exception_response_structure()
    {
        $exception = new \Exception('Test exception');
        
        $response = $this->getExceptionResponse($exception, false);

        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('is_success', $content);
        $this->assertArrayHasKey('status_code', $content);
        $this->assertArrayHasKey('message', $content);
        $this->assertArrayHasKey('errors', $content);
    }

    public function test_get_exception_response_with_reporting_enabled()
    {
        $exception = new \Exception('Test exception');
        
        // Mock the report function to avoid actual reporting during tests
        $mockHandler = $this->mock('Illuminate\Contracts\Debug\ExceptionHandler');
        $mockHandler->shouldReceive('report')->once();
        
        $response = $this->getExceptionResponse($exception, true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function test_get_exception_response_with_debug_mode()
    {
        // Enable debug mode
        config(['app.debug' => true]);
        
        $exception = new \Exception('Test exception');
        
        $response = $this->getExceptionResponse($exception, false);

        $content = json_decode($response->getContent(), true);
        $this->assertEquals('Test exception', $content['message']);
        $this->assertNotEmpty($content['errors']); // Should contain stack trace
        
        // Reset debug mode
        config(['app.debug' => false]);
    }
}
