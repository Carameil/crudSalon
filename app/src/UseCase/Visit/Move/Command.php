<?php

namespace App\UseCase\Visit\Move;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank]
    public int $id;

    #[Assert\NotBlank]
    public string $date;

    #[Assert\NotBlank]
    public string $time;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}