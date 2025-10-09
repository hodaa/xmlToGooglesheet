<?php

namespace App\Strategy;

use App\Exception\NotValidXmlSourceException;
use App\Reader\Xml\XmlReaderAbstract;
use App\Strategy\Contract\InputParserStrategyInterface;
use App\Validator\ConfigurationValidator;
use App\Validator\XmlValidator;
use Generator;

class XmlParserStrategy implements InputParserStrategyInterface
{
    public function __construct(
        private readonly XmlValidator $xmlValidator,
        private readonly ConfigurationValidator $configValidator,
        private readonly XmlReaderAbstract $xmlReaderAbstract
    ) {
        libxml_use_internal_errors(true);
    }

    public function parse(string $xmlSource, array $options = []): Generator| array
    {
        // Validate configuration options first
        $this->configValidator->validateParsingOptions($options);

        $readHeader  = $options['includeHeader'];
        $targetNode = $options['targetNode'];

        if (!$this->xmlValidator->isValidSource($xmlSource)) {
            throw new NotValidXMLSourceException($xmlSource);
        }

        return $this->xmlReaderAbstract->readXmlFile($xmlSource, $targetNode, $readHeader);
    }

}
