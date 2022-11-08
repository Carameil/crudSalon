<?php

namespace App\Entity\Property;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;

class IdType extends IntegerType
{
    public const NAME = 'property_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Id ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Id
    {
        return !empty($value) ? new Id($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }
}