<?php

namespace App\Exception;

use Exception;

class InvalidConfigurationException extends Exception
{
    public static function missingOption(string $optionName): self
    {
        return new self("Required configuration option '$optionName' is missing.");
    }

    public static function invalidOption(string $optionName, mixed $value, string $expectedType): self
    {
        $actualType = gettype($value);
        return new self(
            "Configuration option '$optionName' has invalid value. Expected $expectedType, got $actualType."
        );
    }

    public static function invalidTargetNode(string $targetNode): self
    {
        return new self("Invalid target node '$targetNode'. Target node cannot be empty.");
    }
}