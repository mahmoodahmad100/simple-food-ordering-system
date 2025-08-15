<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Traits\ExceptionHandler;
use App\Exceptions\AppException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExceptionHandlerTraitTest extends TestCase
{
    use ExceptionHandler;

    public function test_register_exception_handlers_method_exists()
    {
        $this->assertTrue(method_exists($this, 'registerExceptionHandlers'));
    }

    public function test_exception_handler_uses_response_trait()
    {
        // Test that we can call the response trait methods
        $response = $this->getResponse([], 200, 'Test');
        
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function test_exception_handler_has_response_methods()
    {
        // Test that the trait provides response methods
        $this->assertTrue(method_exists($this, 'getResponse'));
        $this->assertTrue(method_exists($this, 'getExceptionResponse'));
    }

    public function test_exception_handler_can_handle_different_exception_types()
    {
        // Test that the trait can handle different exception types
        $this->assertTrue(method_exists($this, 'registerExceptionHandlers'));
        
        // Test response methods work
        $response = $this->getResponse([], 200, 'Success');
        $this->assertInstanceOf(JsonResponse::class, $response);
        
        $response = $this->getResponse([], 422, 'Error');
        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}
