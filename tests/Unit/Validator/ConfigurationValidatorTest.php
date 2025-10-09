<?php

namespace App\Tests\Unit\Validator;

use App\Exception\InvalidConfigurationException;
use App\Validator\ConfigurationValidator;
use PHPUnit\Framework\TestCase;

class ConfigurationValidatorTest extends TestCase
{
    private ConfigurationValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new ConfigurationValidator();
    }

    public function testValidateParsingOptionsWithValidOptions(): void
    {
        $validOptions = [
            'targetNode' => 'item',
            'includeHeader' => true
        ];

        // Should not throw any exception
        $this->validator->validateParsingOptions($validOptions);
        $this->assertTrue(true); // Assert test passes if no exception thrown
    }

    public function testValidateParsingOptionsWithMissingTargetNode(): void
    {
        $invalidOptions = [
            'includeHeader' => true
        ];

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage("Required configuration option 'targetNode' is missing.");

        $this->validator->validateParsingOptions($invalidOptions);
    }

    public function testValidateParsingOptionsWithMissingIncludeHeader(): void
    {
        $invalidOptions = [
            'targetNode' => 'item'
        ];

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage("Required configuration option 'includeHeader' is missing.");

        $this->validator->validateParsingOptions($invalidOptions);
    }

    public function testValidateParsingOptionsWithInvalidTargetNodeType(): void
    {
        $invalidOptions = [
            'targetNode' => 123, // Should be string
            'includeHeader' => true
        ];

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage("Configuration option 'targetNode' has invalid value. Expected string, got integer.");

        $this->validator->validateParsingOptions($invalidOptions);
    }

    public function testValidateParsingOptionsWithInvalidIncludeHeaderType(): void
    {
        $invalidOptions = [
            'targetNode' => 'item',
            'includeHeader' => 'yes' // Should be boolean
        ];

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage("Configuration option 'includeHeader' has invalid value. Expected boolean, got string.");

        $this->validator->validateParsingOptions($invalidOptions);
    }

    public function testValidateParsingOptionsWithEmptyTargetNode(): void
    {
        $invalidOptions = [
            'targetNode' => '',
            'includeHeader' => true
        ];

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage("Invalid target node ''. Target node cannot be empty.");

        $this->validator->validateParsingOptions($invalidOptions);
    }

    public function testValidateParsingOptionsWithWhitespaceOnlyTargetNode(): void
    {
        $invalidOptions = [
            'targetNode' => '   ',
            'includeHeader' => true
        ];

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage("Invalid target node '   '. Target node cannot be empty.");

        $this->validator->validateParsingOptions($invalidOptions);
    }

    public function testGetRequiredOptions(): void
    {
        $requiredOptions = $this->validator->getRequiredOptions();

        $this->assertIsArray($requiredOptions);
        $this->assertContains('targetNode', $requiredOptions);
        $this->assertContains('includeHeader', $requiredOptions);
        $this->assertCount(2, $requiredOptions);
    }
}
