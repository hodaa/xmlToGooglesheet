<?php

namespace App\ValueObject;

use App\Exception\InvalidConfigurationException;

/**
 * Value object for parsing configuration
 * Encapsulates parsing options with validation
 */
readonly class ParsingConfiguration
{
    public function __construct(
        public string $targetNode,
        public bool $includeHeader,
        public int $chunkSize = 1000
    ) {
        $this->validate();
    }

    public static function fromArray(array $options): self
    {
        if (!isset($options['targetNode'])) {
            throw InvalidConfigurationException::missingOption('targetNode');
        }

        if (!isset($options['includeHeader'])) {
            throw InvalidConfigurationException::missingOption('includeHeader');
        }

        return new self(
            targetNode: $options['targetNode'],
            includeHeader: $options['includeHeader'],
            chunkSize: $options['chunkSize'] ?? 1000
        );
    }

    public function toArray(): array
    {
        return [
            'targetNode' => $this->targetNode,
            'includeHeader' => $this->includeHeader,
            'chunkSize' => $this->chunkSize
        ];
    }

    private function validate(): void
    {
        if (empty(trim($this->targetNode))) {
            throw InvalidConfigurationException::invalidTargetNode($this->targetNode);
        }

        if ($this->chunkSize <= 0) {
            throw new InvalidConfigurationException('Chunk size must be positive integer');
        }
    }
}