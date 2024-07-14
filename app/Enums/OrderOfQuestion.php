<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum OrderOfQuestion: int
{
    use InvokableCases, Names, Options, Values;

    case RANDOM = 0;
    case ORDERED = 1;

    public static function getList()
    {
        return [
            self::RANDOM() => 'Acak',
            self::ORDERED() => 'Berurut',
        ];
    }
}
