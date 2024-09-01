<?php

namespace Spacers\Framework\Http\HttpException;
use Spacers\Framework\Http\HTTP;

class NotFoundExcetion extends \Exception
{
    const STATUS_CODE = HTTP::NOT_FOUND;

    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, \Throwable $previous = null)
    {
        http_response_code(self::STATUS_CODE);

        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }

    // custom string representation of object
    public function __toString(): string
    {
        return __CLASS__ . " {$this->message}\n";
    }
}