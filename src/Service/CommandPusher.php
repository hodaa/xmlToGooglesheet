<?php

namespace App\Service;

use App\Contract\OutputAdapter;
use App\Service\XmlParserGenerator;

class CommandPusher
{
    public function __construct(private XmlParserGenerator $xmlParserGenerator, private OutputAdapter $adapter)
    {
        $this->adapter = $adapter;
        $this->xmlParserGenerator = $xmlParserGenerator;
    }

    public function execute($xmlFile): void
    {
        $nodes = $this->xmlParserGenerator->parse($xmlFile);

        foreach ($nodes as $item) {
            $this->adapter->push($item);
        }
    }
}
