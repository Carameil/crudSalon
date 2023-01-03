<?php

namespace App\UseCase\Visit\Close;

use Symfony\Component\Validator\Constraints as Assert;

class MaterialCommand
{
    #[Assert\NotBlank]
    public int $materialId;
    #[Assert\NotBlank]
    public int $quantity;
    #[Assert\NotBlank]
    public string $unit;

}