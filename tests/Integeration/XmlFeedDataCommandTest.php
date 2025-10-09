<?php

use App\Service\GoogleSheetsService;
use App\Validator\XmlValidator;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class XmlFeedDataCommandTest extends KernelTestCase
{
    public function testCommandWithFileReturnsSuccess(): void
    {
        $paramsMock = $this->createMock(ParameterBagInterface::class);

        $paramsMock->method('get')
           ->willReturnMap([
            ['chunk_size', 10],
            ['xml.target_node', 'item'],
            ['source_type', 'remote'],
        ]);

        self::bootKernel();
        $application = new Application(self::$kernel);
        $command = $application->find('xml:feed-data');
        $tester = new CommandTester($command);

        $mockClient = $this->createMock(GoogleSheetsService::class);
        $mockClient->expects($this->once())
            ->method('push')
            ->willReturn(true);

        self::getContainer()->set(GoogleSheetsService::class, $mockClient);
        $exitCode = $tester->execute(['xmlSource' => __DIR__.'/../feeds_test.xml']);

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Data successfully pushed to Google Sheets', $tester->getDisplay());
    }

    public function testCommandWithUrlReturnsSuccess(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $command = $application->find('xml:feed-data');
        $tester = new CommandTester($command);

        $paramsMock = $this->createMock(ParameterBagInterface::class);
        $paramsMock->method('get')->willReturnMap([
        ['chunk_size', 10],
        ['xml.target_node', 'PLANT'],
        ['source_type', 'remote'],
        ]);

        $mockValidator = $this->createMock(XmlValidator::class);
        $mockValidator->expects($this->once())
            ->method('isValidSource')
            ->willReturn(true);

        self::getContainer()->set(XmlValidator::class, $mockValidator);

        $mockClient = $this->createMock(GoogleSheetsService::class);
        $mockClient->expects($this->once())
            ->method('push')
            ->willReturn(true);

        self::getContainer()->set(GoogleSheetsService::class, $mockClient);
        $exitCode = $tester->execute(['xmlSource' => 'https://www.w3schools.com/xml/plant_catalog.xml']);

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Data successfully pushed to Google Sheets', $tester->getDisplay());
    }
}
