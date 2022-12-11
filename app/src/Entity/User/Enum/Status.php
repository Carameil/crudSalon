<?php

namespace App\Entity\User\Enum;

enum Status: string
{
    case STATUS_WAIT = 'wait';
    case STATUS_ACTIVE = 'active';
    case STATUS_BLOCKED = 'blocked';
}