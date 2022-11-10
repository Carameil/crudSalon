<?php

namespace App\Entity;

use App\Entity\Property\Enum\Unit;
use App\Repository\MaterialsServicesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MaterialsServicesRepository::class)]
class MaterialsServices
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Material::class)]
    #[ORM\JoinColumn(name: 'material_id', nullable: false)]
    private Material $material;

    #[ORM\ManyToOne(targetEntity: Service::class)]
    #[ORM\JoinColumn(name: 'service_id', nullable: false)]
    private Service $service;

    #[ORM\Column(name: 'quantity_material', type: "integer", options: ['default' => 1])]
    #[Assert\GreaterThan(0)]
    private string $quantityMaterial;

    #[ORM\Column(type: "property_unit", length: 5, options: ['default' => Unit::Thing])]
    private Unit $unit;

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getQuantityMaterial(): string
    {
        return $this->quantityMaterial;
    }

    /**
     * @return Unit
     */
    public function getUnit(): Unit
    {
        return $this->unit;
    }

    /**
     * @param string $quantityMaterial
     */
    public function setQuantityMaterial(string $quantityMaterial): void
    {
        $this->quantityMaterial = $quantityMaterial;
    }

    /**
     * @param Unit $unit
     */
    public function setUnit(Unit $unit): void
    {
        $this->unit = $unit;
    }

    public function getMaterial(): ?Material
    {
        return $this->material;
    }

    public function setMaterial(?Material $material): self
    {
        $this->material = $material;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }
}
