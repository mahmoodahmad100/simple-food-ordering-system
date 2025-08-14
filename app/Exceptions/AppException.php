<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class AppException extends Exception
{
    /**
     * @var int
     */
    protected $http_status_code;

    /**
     * @param string $message
     * @param int $http_status_code
     * @param int $code
     * @param Throwable $previous
     */
    public function __construct(
        string $message = 'There is a problem in our side, we will fix that very soon.',
        int $http_status_code = 500,
        int $code = 0,
        Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
        $this->http_status_code = $http_status_code;
    }

    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return $this->http_status_code;
    }
}