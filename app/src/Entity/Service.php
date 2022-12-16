<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity]
#[UniqueEntity('name')]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: Position::class, inversedBy: 'services')]
    private Position $position;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'services')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    #[Assert\GreaterThan(0)]
    private int $price;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'integer')]
    private ?string $avgTime = null;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: MaterialsServices::class)]
    private ?Collection $materials = null;

    public static function create(string $name, Category $category, Position $position, int $price, int $avgTime, ?string $description = null): self
    {
        $service = new static();
        $service->name = $name;
        $service->category = $category;
        $service->position = $position;
        $service->price = $price;
        $service->description = $description;
        $service->avgTime = $avgTime;

        return $service;
    }

    public function getId(): int
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

    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getAvgTime(): string
    {
        return $this->avgTime;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
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


    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @param string $avgTime
     */
    public function setAvgTime(string $avgTime): void
    {
        $this->avgTime = $avgTime;
    }

    public function getMaterialServices(): ?Collection
    {
        return $this->materials;
    }

    public function addMaterialServices(MaterialsServices $materialsServices): self
    {
        if (!$this->materials->contains($materialsServices)) {
            $this->materials->add($materialsServices);
            $materialsServices->setService($this);
        }

        return $this;
    }

    public function removeMaterialServices(MaterialsServices $materialsServices): self
    {
        $this->materials->removeElement($materialsServices);

        return $this;
    }
}
