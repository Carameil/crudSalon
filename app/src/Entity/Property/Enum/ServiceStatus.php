<?php

namespace App\Entity\Property\Enum;

enum ServiceStatus: string
{
    public const ACTIVE = 'Активно';
    public const CLOSED = 'Закрыто';
    public const CANCELED = 'Отменено';
}