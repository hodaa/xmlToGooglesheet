<?php

namespace App\Contract;

use Generator;

interface OutputAdapterGenerator
{
    public function push(Generator $data): bool;
}
