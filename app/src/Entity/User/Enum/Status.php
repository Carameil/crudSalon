<?php

namespace App\Entity\User\Enum;

enum Status: string
{
    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_BLOCKED = 'blocked';
}