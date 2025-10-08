<?php

namespace App\Exception;

use RuntimeException;
use \Throwable;
class NotValidXmlSourceException extends RuntimeException
{
    public function __construct(string $message = 'The provided XML source is not valid.', $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
