<?php

namespace App\Entity;

use App\Entity\User\User;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client extends User
{
    public const TYPE = 'client';

    #[ORM\Column(type: "string", length: 15)]
    private string $phone;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Visit::class)]
    private Collection $visits;

    public function __construct(string $firstName, $lastName, string $email, string $phone, $middleName = null)
    {
        parent::__construct($firstName, $lastName, $email, $middleName);
        $this->phone = $phone;
        $this->addRole(self::ROLE_USER);
        $this->visits = new ArrayCollection();
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getType(): string
    {
        return parent::TYPE_CLIENT;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return Collection<int, Visit>
     */
    public function getVisits(): Collection
    {
        return $this->visits;
    }

    public function addVisit(Visit $visit): self
    {
        if (!$this->visits->contains($visit)) {
            $this->visits->add($visit);
            $visit->setClient($this);
        }

        return $this;
    }

    public function removeVisit(Visit $visit): self
    {
        if ($this->visits->removeElement($visit)) {
            // set the owning side to null (unless already changed)
            if ($visit->getClient() === $this) {
                $visit->setClient(null);
            }
        }

        return $this;
    }


}
