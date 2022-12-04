<?php

namespace App\Entity;

use App\Entity\Property\Enum\ServiceStatus;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity]
#[UniqueEntity(fields: ['date', 'time'], message: 'Данная запись уже существует')]
#[ORM\UniqueConstraint(name: 'date_time_ui', columns: ['date', 'time'])]
class Visit
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Service::class)]
    #[ORM\JoinColumn(name: 'service_id', nullable: false)]
    private Service $service;

    #[ORM\ManyToOne(targetEntity: Employee::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Employee $employee;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'visits')]
    #[ORM\JoinColumn(nullable: false)]
    private Client $client;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeInterface $time;

    #[ORM\Column(type: 'string', length: 50, nullable: false, options: ['default' => ServiceStatus::ACTIVE])]
    private string $serviceStatus = ServiceStatus::ACTIVE;

    public static function create(Service $service, Employee $employee, Client $client, \DateTimeInterface $date, \DateTimeInterface $time): self
    {
        $visit = new self();
        $visit->service = $service;
        $visit->employee = $employee;
        $visit->client = $client;
        $visit->date = $date;
        $visit->time = $time;

        return $visit;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function getTime(): \DateTimeInterface
    {
        return $this->time;
    }

    public function getService(): Service
    {
        return $this->service;
    }

    public function setService(Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    public function setEmployee(Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function setTime(\DateTimeInterface $time): void
    {
        $this->time = $time;
    }

    public function getServiceStatus(): string
    {
        return $this->serviceStatus;
    }

    public function setServiceStatus(string $serviceStatus): self
    {
        $this->serviceStatus = $serviceStatus;

        return $this;
    }

}
