<?php

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class XmlFeedDataCommandTest extends KernelTestCase
{
    public function testCommandReturnsSuccess(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $command = $application->find('xml:feed-data');
        $tester = new CommandTester($command);

        $exitCode = $tester->execute([ 'xmlSource' => '../feeds_test.xml']);

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Import completed', $tester->getDisplay());
    }
}
