<?php

namespace App\Entity\User;

use App\Entity\Client;
use App\Entity\Employee;
use App\Entity\Property\Email;
use App\Entity\User\Enum\Status;
use App\Entity\User\Enum\Subordinate;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'type', type: 'string')]
#[DiscriminatorMap([
        'client' => Client::class,
        'employee' => Employee::class,
        'user' => User::class
])]

class User extends AbstractedUser implements PasswordAuthenticatedUserInterface, UserInterface
{

    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 80)]
    protected string $firstName;

    #[ORM\Column(type: 'string', length: 80)]
    protected string $lastName;

    #[ORM\Column(type: 'string', length: 80, nullable: true)]
    protected ?string $middleName = null;

    #[ORM\Column(type: "property_email", length: 180, unique:true)]
    protected Email $email;

    #[ORM\Column(type: "property_status", length: 16, options: ['default' => Status::STATUS_ACTIVE])]
    private Status $status;

    #[ORM\Column(name: "passwordHash", type: "string", nullable: true)]
    private ?string $passwordHash = null;

    #[ORM\Column(type: "json")]
    protected array $roles = [];

    protected function __construct($firstName, $lastName, Email $email, $middleName = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;
        $this->email = $email;
        $this->status = Status::STATUS_ACTIVE;
    }

    public static function create(
            string $firstName,
            string $lastName,
            Email $email,
            string $passwordHash = null,
            string $middleName = null,
    ): self
    {
        $user = new self($firstName, $lastName, $email, $middleName);
        $user->email = $email;
        $user->passwordHash = $passwordHash;
        $user->addRole(self::ROLE_ADMIN);
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
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->passwordHash;
    }

    /**
     * @param string|null $passwordHash
     */
    public function setPassword(?string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = self::ROLE_USER;


        return array_unique($roles);
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @param string|null $middleName
     */
    public function setMiddleName(?string $middleName): void
    {
        $this->middleName = $middleName;
    }

    /**
     * @param Status $status
     */
    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function isClient(string $subordinate): bool
    {
        return Subordinate::SUB_CLIENT === $subordinate;
    }

    public function isEmployee(string $subordinate): bool
    {
        return Subordinate::SUB_EMPLOYEE === $subordinate;
    }

    public function changeStatus(Status $status): void
    {
        if ($this->status === $status) {
            throw new \DomainException('Status is already same.');
        }
        $this->status = $status;
    }

    public function getUserIdentifier(): string
    {
        return $this->email->getValue();
    }
}
