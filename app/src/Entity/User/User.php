<?php

namespace App\Entity\User;

use App\Entity\Client;
use App\Entity\Employee;
use App\Entity\Property\Email;
use App\Entity\Property\Id;
use App\Entity\Property\Role;
use App\Entity\User\Enum\Status;
use App\Entity\User\Enum\Subordinate;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'type', type: 'string')]
#[DiscriminatorMap([
        'client' => Client::class,
        'employee' => Employee::class
])]

class User extends AbstractedUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "property_id")]
    protected Id $id;

    #[ORM\Column(type: 'string', length: 80)]
    protected string $firstName;

    #[ORM\Column(type: 'string', length: 80)]
    protected string $lastName;

    #[ORM\Column(type: 'string', length: 80)]
    protected ?string $middleName = null;

    #[ORM\Column(type: "property_email", length: 180, unique:true)]
    protected Email $email;

    #[ORM\Column(type: "property_status", length: 16, options: ['default' => Status::STATUS_ACTIVE])]
    private Status $status;

    #[ORM\Column(name: "passwordHash", type: "string", nullable: true)]
    private ?string $passwordHash = null;

    #[ORM\Column(type: "property_role", length: 16)]
    private Role $role;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'create')]
    protected ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'update')]
    protected ?\DateTimeInterface $updatedAt = null;

    protected function __construct(Id $id, $firstName, $lastName, Email $email, $middleName = null)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;
        $this->email = $email;
        $this->status = Status::STATUS_ACTIVE;
        $this->role = Role::user();
    }

    public static function create(
            Id $id,
            string $firstName,
            string $lastName,
            Email $email,
            string $middleName = null,
            string $passwordHash = null
    ): self
    {
        $user = new self($id, $firstName, $lastName, $email, $middleName);
        $user->passwordHash = $passwordHash;

        return $user;
    }


    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @return Role
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @return string|null
     */
    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    /**
     * @param string|null $passwordHash
     */
    public function setPasswordHash(?string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    /**
     * @param Id $id
     */
    public function setId(Id $id): void
    {
        $this->id = $id;
    }

    /**
     * @param Status $status
     */
    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    /**
     * @param Role $role
     */
    public function setRole(Role $role): void
    {
        $this->role = $role;
    }

    public function isClient(string $subordinate): bool
    {
        return Subordinate::SUB_CLIENT === $subordinate;
    }

    public function isEmployee(string $subordinate): bool
    {
        return Subordinate::SUB_EMPLOYEE === $subordinate;
    }
}
