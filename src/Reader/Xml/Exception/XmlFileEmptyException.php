<?php

namespace App\Reader\Xml\Exception;

use RuntimeException;

class XmlFileEmptyException extends RuntimeException
{
    public function __construct(string $message = 'The provided XML is empty.', $code = 0)
    {
        parent::__construct($message, $code);
    }
}
