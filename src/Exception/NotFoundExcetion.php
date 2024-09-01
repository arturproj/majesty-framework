<?php

namespace Spacers\Framework\Exception;
use Spacers\Framework\Constant\HTTP;

class NotFoundExcetion extends \Exception
{
    // Redefine the exception so message isn't optional
    public function __construct($message, $code = HTTP::NOT_FOUND, \Throwable $previous = null)
    {

        http_response_code($code);

        // make sure everything is assigned properly
        parent::__construct("NotFoundExcetion:\n$message", $code, $previous);
    }
}