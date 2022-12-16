<?php

namespace App\UseCase\Visit\Close;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank]
    public int $id;

    /**
     * @var array<MaterialCommand> $variants
     */
    public array $materials;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}