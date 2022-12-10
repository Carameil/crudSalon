<?php

namespace App\UseCase\Visit\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank]
    public int $clientId;

    #[Assert\NotBlank]
    public int $employeeId;

    #[Assert\NotBlank]
    public int $serviceId;

    #[Assert\NotBlank]
    public string $date;

    #[Assert\NotBlank]
    public string $time;

    public function __construct(int $clientId)
    {
        $this->clientId = $clientId;
    }
}