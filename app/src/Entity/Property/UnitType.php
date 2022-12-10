<?php

namespace App\Entity\Property;

use App\Entity\Property\Enum\Unit;


class UnitType extends AbstractEnumType
{
    public const NAME = 'property_unit';

    public function getName(): string // the name of the type.
    {
        return self::NAME;
    }

    public static function getEnumsClass(): string // the enums class to convert
    {
        return Unit::class;
    }

}