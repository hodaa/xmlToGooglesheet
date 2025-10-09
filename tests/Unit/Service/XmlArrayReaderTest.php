<?php

namespace App\Tests\Unit\Service;

use App\Reader\Xml\Exception\XmlFileEmptyException;
use App\Reader\Xml\XmlArrayReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class XmlArrayReaderTest extends TestCase
{
    private string $xmlTestFile;
    private XmlArrayReader $xmlReader;

    protected function setUp(): void
    {
        parent::setUp();

        $paramsMock = $this->createMock(ParameterBagInterface::class);

        $paramsMock->method('get')->willReturnMap([
         ['chunk_size', 10],
         ['source_type', 'local'],
        ]);
        $this->xmlTestFile = __DIR__ . '/../../fake/feeds_test.xml';

        $this->xmlReader = new XmlArrayReader($paramsMock);
    }

    public function testReadItemsReturnsGenerator(): void
    {
        $rows = $this->xmlReader->readXMLFile($this->xmlTestFile, 'item');
        $this->assertInstanceOf(\Generator::class, $rows);

    }

    public function testReadItemsThrowsEmptyException(): void
    {
        $this->expectException(XmlFileEmptyException::class);

        // XML فاضي
        $emptyXml = __DIR__ . '/../../fake/feeds_empty.xml';
        $this->xmlReader->readXMLFile($emptyXml, 'item');
    }

}
