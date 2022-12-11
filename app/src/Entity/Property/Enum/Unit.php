<?php

namespace App\Entity\Property\Enum;

use App\Utils\Traits\EnumToArray;

enum Unit: string
{
    use EnumToArray;
    case THING = 'шт';
    case LITER = 'л';
    case MILLILITER = 'мл';
    case PACKING = 'уп';
}