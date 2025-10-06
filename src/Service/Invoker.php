<?php

namespace App\Service;

use App\Contract\OutputAdapter;

class Invoker
{
    private OutputAdapter $outputAdapter;

    public function __construct(OutputAdapter $outputAdapter)
    {
        $this->outputAdapter = $outputAdapter;
    }

    // public function excutePush(array $data): void
    // {

    //     $iterator = XmlStreamIterator::fromFile($xmlFile);

    //     foreach ($iterator as $record) {
    //         $command = new WriteCommand($adapter, [$record], $strategy);
    //         $command->execute();
    //     }
    // }

}
