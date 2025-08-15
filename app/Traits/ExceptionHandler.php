<?php

namespace App\Traits;

use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\AppException;
use Illuminate\Http\Request;

trait ExceptionHandler
{
    use Response;

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function registerExceptionHandlers()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Handle AuthenticationException
        $this->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return $this->getResponse([], 401, $e->getMessage());
            }
        });

        // Handle AuthorizationException
        $this->renderable(function (AuthorizationException $e, Request $request) {
            if ($request->expectsJson()) {
                return $this->getResponse([], 403, $e->getMessage());
            }
        });

        // Handle ValidationException
        $this->renderable(function (ValidationException $e, Request $request) {
            if ($request->expectsJson()) {
                $errors = [];
                
                foreach ($e->errors() as $field => $error) {
                    $errors[] = [
                        'field'   => $field,
                        'message' => is_array($error) ? $error[0] : $error,
                    ];
                }
        
                return $this->getResponse($errors, 422, $e->getMessage());
            }
        });

        // Handle AppException
        $this->renderable(function (AppException $e, Request $request) {
            if ($request->expectsJson()) {
                return $this->getResponse([], $e->getHttpStatusCode(), $e->getMessage());
            }
        });
        
        // Handle all other exceptions
        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->expectsJson()) {
                return $this->getExceptionResponse($e, true);
            }
        });
    }
}