<?php

namespace App\Entity\Property\Enum;

enum ServiceStatus: string
{
    case ACTIVE = 'Активно';

    case CLOSED = 'Закрыто';
    case CANCELED = 'Отменено';
}