<?php

namespace App\Contract;

use Generator;

interface OutputAdapter
{
    public function push(array $data): bool;
}
