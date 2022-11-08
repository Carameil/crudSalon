<?php

namespace App\Entity\Property;


use App\Entity\User\Enum\Status;

class StatusType extends AbstractEnumType
{
    public const NAME = 'property_status';

    public function getName(): string // the name of the type.
    {
        return self::NAME;
    }

    public static function getEnumsClass(): string // the enums class to convert
    {
        return Status::class;
    }
}