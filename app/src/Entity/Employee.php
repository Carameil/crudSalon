<?php

namespace App\Entity;

use App\Entity\Property\Email;
use App\Entity\Property\Id;
use App\Entity\User\User;
use App\Repository\EmployeeRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee extends User
{
    public const TYPE = 'employee';

    #[ORM\Column(type: "string", length: 15)]
    private string $phone;

    #[ORM\Column(type: "string", length: 100)]
    private ?string $address = null;

    #[ORM\ManyToOne(inversedBy: 'employees')]
    private Position $position;

    public function __construct(Id $id, string $firstName, $lastName, Email $email, $middleName = null)
    {
        parent::__construct($id, $firstName, $lastName, $email, $middleName);
        $this->visits = new ArrayCollection();
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
