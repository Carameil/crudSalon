<?php

namespace App\Entity\User\Enum;

enum Subordinate: string
{
    case SUB_EMPLOYEE = 'employee';
    case SUB_CLIENT = 'client';
}