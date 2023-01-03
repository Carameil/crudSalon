<?php

declare(strict_types=1);

namespace App\ReadModel\Visit\Filter;

use DateTimeInterface;

class Filter
{
    public ?string $fullName = null;
    public ?string $service = null;
    public ?DateTimeInterface $date = null;
}
