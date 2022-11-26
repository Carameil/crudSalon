<?php

namespace App\UseCase\Client\SignUp;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    public string $firstName;

    #[Assert\NotBlank]
    public string $lastName;

    #[Assert\NotBlank]
    public string $middleName;

    #[Assert\NotBlank]
    public string $phone;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    public string $plainPassword;
}