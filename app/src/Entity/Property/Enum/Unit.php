<?php

namespace App\Entity\Property\Enum;

enum Unit: string
{
    case Thing = 'шт';
    case Liter = 'л';
    case Milliliter = 'мл';
    case Packing = 'уп';
}