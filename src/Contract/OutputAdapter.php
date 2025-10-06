<?php

namespace App\Contract;

interface OutputAdapter
{
    public function push(array $data): bool;
}
