<?php

namespace App\Entity;

use App\Entity\Property\Enum\Unit;
use App\Entity\Property\Id;
use App\Repository\MaterialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MaterialRepository::class)]
class Material
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "property_id")]
    private Id $id;

    #[ORM\Column(type: "string", length: 100)]
    private string $name;

    #[ORM\Column(type: "string", length: 100)]
    private string $manufacturer;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: "string", length: 100)]
    private string $supplier;

    #[ORM\Column(type: "integer", options: ['default' => 1])]
    #[Assert\GreaterThan(0)]
    private string $quantity;

    #[ORM\Column(type: "property_unit", length: 5)]
    private Unit $unit;

    public function getId(): Id
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
     * @return string
     */
    public function getManufacturer(): string
    {
        return $this->manufacturer;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getSupplier(): string
    {
        return $this->supplier;
    }

    /**
     * @return string
     */
    public function getQuantity(): string
    {
        return $this->quantity;
    }

    /**
     * @return Unit
     */
    public function getUnit(): Unit
    {
        return $this->unit;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $manufacturer
     */
    public function setManufacturer(string $manufacturer): void
    {
        $this->manufacturer = $manufacturer;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param string $supplier
     */
    public function setSupplier(string $supplier): void
    {
        $this->supplier = $supplier;
    }

    /**
     * @param string $quantity
     */
    public function setQuantity(string $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @param Unit $unit
     */
    public function setUnit(Unit $unit): void
    {
        $this->unit = $unit;
    }
}
