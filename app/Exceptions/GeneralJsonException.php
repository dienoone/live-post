<?php

namespace App\Exceptions;

use Exception;

class GeneralJsonException extends Exception
{
    public function __construct(
        string $message,
        private readonly string $errorCode = 'BUSINESS_ERROR',
        int $statusCode = 400
    ) {
        parent::__construct($message, $statusCode);
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
