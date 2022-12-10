<?php

namespace App\Entity\Property\Enum;

enum Unit: string
{
    public const THING = 'шт';
    public const LITER = 'л';
    public const MILLILITER = 'мл';
    public const PACKING = 'уп';
}