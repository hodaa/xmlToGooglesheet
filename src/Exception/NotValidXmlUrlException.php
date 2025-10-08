<?php

namespace App\Exception;

use RuntimeException;
use Throwable;

class NotValidXmlUrlException extends RuntimeException
{
    public function __construct(?string $path, ?Throwable $previous = null)
    {
        $message = "The provided XML URL is not valid. {$path}";
        parent::__construct($message, 0, $previous);
    }
}
