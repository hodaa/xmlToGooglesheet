<?php

namespace App\Exception;

use Exception;

class NotFoundException extends Exception
{
    public function __construct($path)
    {
        parent::__construct("File not found at the given path: {$path}", 404, null);
    }
}
