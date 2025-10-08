<?php

namespace App\Enum;

enum SourceType: string
{
    case LOCAL = 'local';
    case REMOTE = 'remote';
}
