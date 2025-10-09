<?php

namespace App\Service;

use App\Enum\SourceType;

/**
 * Detects source type dynamically based on input
 * Replaces hardcoded source type configuration
 */
class SourceDetector
{
    public function detectSourceType(string $source): SourceType
    {
        if ($this->isUrl($source)) {
            return SourceType::REMOTE;
        }
        
        return SourceType::LOCAL;
    }

    private function isUrl(string $source): bool
    {
        return filter_var($source, FILTER_VALIDATE_URL) !== false;
    }
}