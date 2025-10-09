<?php

namespace App\Validator;

use App\Exception\InvalidConfigurationException;

class ConfigurationValidator
{
    private const REQUIRED_OPTIONS = ['targetNode', 'includeHeader'];
    
    /**
     * Validate parsing options for XML processing
     *
     * @param array $options The options array to validate
     * @throws InvalidConfigurationException If validation fails
     */
    public function validateParsingOptions(array $options): void
    {
        $this->validateRequiredOptions($options);
        $this->validateOptionTypes($options);
        $this->validateTargetNode($options['targetNode']);
    }

    /**
     * Validate that all required options are present
     *
     * @param array $options
     * @throws InvalidConfigurationException
     */
    private function validateRequiredOptions(array $options): void
    {
        foreach (self::REQUIRED_OPTIONS as $requiredOption) {
            if (!array_key_exists($requiredOption, $options)) {
                throw InvalidConfigurationException::missingOption($requiredOption);
            }
        }
    }

    /**
     * Validate that options have correct types
     *
     * @param array $options
     * @throws InvalidConfigurationException
     */
    private function validateOptionTypes(array $options): void
    {
        // Validate targetNode is string
        if (isset($options['targetNode']) && !is_string($options['targetNode'])) {
            throw InvalidConfigurationException::invalidOption(
                'targetNode', 
                $options['targetNode'], 
                'string'
            );
        }

        // Validate includeHeader is boolean
        if (isset($options['includeHeader']) && !is_bool($options['includeHeader'])) {
            throw InvalidConfigurationException::invalidOption(
                'includeHeader', 
                $options['includeHeader'], 
                'boolean'
            );
        }
    }

    /**
     * Validate target node value
     *
     * @param string $targetNode
     * @throws InvalidConfigurationException
     */
    private function validateTargetNode(string $targetNode): void
    {
        if (empty(trim($targetNode))) {
            throw InvalidConfigurationException::invalidTargetNode($targetNode);
        }
    }

    /**
     * Get list of required options
     *
     * @return array
     */
    public function getRequiredOptions(): array
    {
        return self::REQUIRED_OPTIONS;
    }
}