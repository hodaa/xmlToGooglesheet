<?php

namespace App\Tests\Factory;

use App\Factory\InputParserFactory;
use App\Strategy\CsvParserStrategy;
use App\Strategy\XmlParserStrategy;
use PHPUnit\Framework\TestCase;

class InputParserFactoryTest extends TestCase
{
    private XmlParserStrategy $xmlMock;
    private CsvParserStrategy $csvMock;
    private InputParserFactory $factory;

    protected function setUp(): void
    {
        // create mocks for the strategies
        $this->xmlMock = $this->createMock(XmlParserStrategy::class);
        $this->csvMock = $this->createMock(CsvParserStrategy::class);

        // instantiate the factory with mocks
        $this->factory = new InputParserFactory($this->xmlMock, $this->csvMock);
    }

    public function testGetStrategyReturnsXmlStrategy(): void
    {
        $strategy = $this->factory->getStrategy('xml');
        $this->assertSame($this->xmlMock, $strategy);
    }

    public function testGetStrategyReturnsCsvStrategy(): void
    {
        $strategy = $this->factory->getStrategy('csv');
        $this->assertSame($this->csvMock, $strategy);
    }

    public function testGetStrategyThrowsExceptionForUnknownType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown parser type unknown');

        $this->factory->getStrategy('unknown');
    }

    public function testGetStrategyIsCaseInsensitive(): void
    {
        $strategy = $this->factory->getStrategy('XML'); // uppercase
        $this->assertSame($this->xmlMock, $strategy);
    }
}
