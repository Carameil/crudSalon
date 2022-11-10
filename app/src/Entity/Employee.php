<?php

namespace App\Entity;

use App\Entity\Property\Email;
use App\Entity\Property\Role;
use App\Entity\User\User;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee extends User
{
    public const TYPE = 'employee';

    #[ORM\Column(type: "string", length: 15)]
    protected string $phone;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    protected ?string $address = null;

    #[ORM\ManyToOne(inversedBy: 'employees')]
    private Position $position;

    public function __construct(string $firstName, $lastName, Email $email, $middleName = null)
    {
        parent::__construct($firstName, $lastName, $email, $middleName);
        $this->changeRole(Role::employee());
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getAddress(): ?string
    {
        return $this->phone;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function setPosition(Position $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }
}
