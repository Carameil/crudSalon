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
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Material::class)]
    #[ORM\JoinColumn(name: 'material_id', nullable: false)]
    private Material $material;

    #[ORM\ManyToOne(targetEntity: Service::class, inversedBy: 'materials')]
    #[ORM\JoinColumn(name: 'service_id', nullable: false)]
    private Service $service;

    #[ORM\Column(name: 'quantity_material', type: "integer", options: ['default' => 1])]
    #[Assert\GreaterThan(0)]
    private string $quantityMaterial;

    #[ORM\Column(type: "string", length: 5, options: ['default' => Unit::THING])]
    private string $unit = Unit::THING;

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
     * @return string
     */
    public function getUnit(): string
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
     * @param string $unit
     */
    public function setUnit(string $unit): void
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
