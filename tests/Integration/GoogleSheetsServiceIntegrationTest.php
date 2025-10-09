<?php

namespace App\Tests\Integration;

use App\Service\GoogleSheetsService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GoogleSheetsServiceIntegrationTest extends TestCase
{
    private LoggerInterface $logger;
    private ParameterBagInterface $params;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);


        $this->params = $this->createMock(ParameterBagInterface::class);

        $this->params->method('get')
           ->willReturnMap([
            ['chunk_size', 10],
            ['source_type', 'remote'],
            ['google_credentials_path', __DIR__ . '/../fake/credentials.json'],
        ]);

    }

    public function testServiceImplementsOutputAdapterInterface(): void
    {
        $service = new GoogleSheetsService($this->logger, $this->params);

        $this->assertInstanceOf(\App\Contract\OutputAdapter::class, $service);
    }

    public function testPushMethodExists(): void
    {
        $service = new GoogleSheetsService($this->logger, $this->params);

        $this->assertTrue(method_exists($service, 'push'));
    }

    /**
     * Test that demonstrates the current limitation of the service:
     * Hard to mock Google Sheets API calls due to tight coupling
     */
    public function testServiceCreatesGoogleClientInConstructor(): void
    {
        // This test shows that the service creates its own Google Client
        // making it hard to inject mocks for testing

        $service = new GoogleSheetsService($this->logger, $this->params);

        // We can only test that the service was created without exceptions
        $this->assertInstanceOf(GoogleSheetsService::class, $service);
    }

    public function testPushWithValidDataStructure(): void
    {
        $service = new GoogleSheetsService($this->logger, $this->params);
        $testData = [
            ['Column1', 'Column2'],
            ['Value1', 'Value2']
        ];

        // Test that the method accepts the correct data types without throwing type errors
        // We expect this to fail with authentication/API errors, but not type errors
        $exceptionThrown = false;

        try {
            $service->push($testData);
        } catch (\Exception $e) {
            $exceptionThrown = true;
            // We expect some kind of exception since we're using fake credentials
            // but it should not be a type error
            $this->assertNotInstanceOf(\TypeError::class, $e, 'Should not throw type errors');
        }

        // In a test environment, we expect an exception due to invalid credentials
        $this->assertTrue($exceptionThrown, 'Expected an exception due to fake credentials');
    }
}
