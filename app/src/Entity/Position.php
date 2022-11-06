<?php

namespace App\Entity;

use App\Entity\Property\Id;
use App\Repository\PositionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PositionRepository::class)]
class Position
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'property_id')]
    private Id $id;

    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private string $name;

    #[ORM\Column(type: 'decimal', precision: 7, scale: 2)]
    #[Assert\GreaterThan(0)]
    private int $salary;

    #[ORM\OneToMany(mappedBy: 'position', targetEntity: Employee::class)]
    private ?Collection $employees = null;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: Service::class)]
    private ?Collection $services = null;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
    }

    public function getId(): ?Id
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getSalary(): int
    {
        return $this->salary;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param int $salary
     */
    public function setSalary(int $salary): void
    {
        $this->salary = $salary;
    }

    public function getEmployees(): ?Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employee $employee): self
    {
        if (!$this->employees->contains($employee)) {
            $this->employees->add($employee);
            $employee->setPosition($this);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): self
    {
        $this->employees->removeElement($employee);

        return $this;
    }

    public function getServices(): ?Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->employees->contains($service)) {
            $this->employees->add($service);
            $service->setPosition($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        $this->employees->removeElement($service);

        return $this;
    }

}
