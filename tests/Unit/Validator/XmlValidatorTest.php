<?php

namespace App\Tests\Service;

use App\Exception\NotValidXmlSourceException;
use App\Validator\XmlValidator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class XmlValidatorTest extends KernelTestCase
{
    public function testSourceTypeIsValid()
    {
        $paramsMock = $this->createMock(ParameterBagInterface::class);

        $paramsMock->method('get')
            ->with('source_type')         // if you want, can check key
            ->willReturn('local');

        $xmlValidator = new XmlValidator($paramsMock);
        $this->expectException(NotValidXmlSourceException::class);
        $this->expectExceptionMessage('Invalid XML source type');

        // 5️⃣ Call the method that should throw
        $xmlValidator->isValidSource('sheet'); // inv

        //for integration test
        // self::bootKernel();
        // $container = self::getContainer();

        // /** @var ParameterBagInterface $params */
        // $params = $container->get(ParameterBagInterface::class);

        // $xmlValidator = new XmlValidator($params);

        // $this->expectException(NotValidXmlSourceException::class);

        // $xmlValidator->isValidSource('sheet');
    }

}
