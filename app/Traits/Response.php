<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Throwable;

trait Response
{
    /**
     * get the response
     *
     * @param mixed $result
     * @param int $status_code
     * @param string $message
     * @return JsonResponse
     */
    public function getResponse(mixed $result = [], int $status_code = 200, string $message = null): JsonResponse
    {
        $is_success = $status_code < 400; 
        $result_key = $is_success ? 'data' : 'errors';

        if ($message === null) {
            $message = $is_success ? 'Success.' : 'Error.';
        }

        $response   = [
            'is_success'  => $is_success,
            'status_code' => $status_code,
            'message'     => $message,
            $result_key   => $result
        ];

        return response()->json($response, $status_code);
    }

    /**
     * get the exception response
     *
     * @param Throwable $e
     * @param bool $report
     * @return JsonResponse
     */
    protected function getExceptionResponse(Throwable $e, bool $report = true): JsonResponse
    {
        $errors  = [];
        $message = 'OOPS! there is a problem in our side! we got your problem and we will fix that very soon.';

        if ($report) {
            report($e);
        }

        if (env('APP_DEBUG') == true) {
            $errors  = $e->getTrace();
            $message = $e->getMessage();
        }

        return $this->getResponse($errors, 500, $message);
    }
}