<?php

namespace App\Exception;

use Exception;
use Throwable;

class NotFoundException extends Exception
{
    public function __construct(string $path, ?Throwable $previous = null)
    {
        $message = "File not found at the given path: {$path}";
        parent::__construct($message, 404, $previous);
    }
}
