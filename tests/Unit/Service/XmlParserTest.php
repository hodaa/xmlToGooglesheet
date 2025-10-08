<?php



use App\Strategy\XmlParserStrategy;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class XmlParserTest extends KernelTestCase
{
    private string  $xmlTestFile = 'tests/feeds_test.xml';
    private XmlParserStrategy $xmlParserStrategy;


    // protected function setUp(): void
    // {
    //     parent::setUp();
    //     $this->xmlParserService = new xmlParserStrategy();
    // }

    // public function testNumberOfXMLItems(): void
    // {
    //     $rows =  $this->xmlParserService->readXMLFile($this->xmlTestFile);
    //     $this->assertCount(2, $rows);
    //     $this->assertIsArray($rows[0]);
    //     $this->assertEquals(['entity_id', 'CategoryName', 'sku', 'name', 'description', 'shortdesc', 'price',
    //      'link', 'image', 'Brand', 'Rating', 'CaffeineType', 'Count', 'Flavored', 'Seasonal',
    //      'Instock','Facebook','IsKCup'], $rows[0]);

    // }
}
