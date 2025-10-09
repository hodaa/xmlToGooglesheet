<?php

namespace App\Tests\Reader\Xml;

use App\Exception\NotValidXMLSourceException;
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
         ['xml.target_node', 'item'],
         ['source_type', 'local'],
        ]);
        $this->xmlTestFile = __DIR__ . 'feeds_test.xml';

        $this->xmlReader = new XmlArrayReader($paramsMock);
    }

    public function testReadItemsReturnsArray(): void
    {
        $rows = $this->xmlReader->readXMLFile($this->xmlTestFile);
        $this->assertIsArray($rows);
        $this->assertNotEmpty($rows);
    }

    // public function testReadItemsThrowsEmptyException(): void
    // {
    //     $this->expectException(XmlFileEmptyException::class);

    //     // XML فاضي
    //     $emptyXml = __DIR__ . '/feeds_empty.xml';
    //     $this->xmlReader->readXMLFile($emptyXml);
    // }

    // public function testReadItemsThrowsNotValidXMLSourceException(): void
    // {
    //     $this->expectException(NotValidXMLSourceException::class);

    //     // XML غير صالح
    //     $invalidXml = __DIR__ . '/feeds_invalid.xml';
    //     $this->xmlReader->readXMLFile($invalidXml);
    // }

    // public function testChunkDataYieldsChunks(): void
    // {
    //     $rows = [
    //         ['col1', 'col2'],
    //         ['data1', 'data2'],
    //         ['data3', 'data4'],
    //     ];

    //     $this->xmlReader->setChunkSize(2);

    //     $chunks = iterator_to_array($this->xmlReader->chunkData($rows));
    //     $this->assertCount(2, $chunks); // 3 صفوف → chunkSize 2 → 2 chunks
    //     $this->assertEquals([['col1','col2'], ['data1','data2']], $chunks[0]);
    // }
}
