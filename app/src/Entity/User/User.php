<?php

namespace App\Entity\User;

use App\Entity\Client;
use App\Entity\Employee;
use App\Entity\User\Enum\Status;
use App\Entity\User\Enum\Subordinate;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: '`user`')]
#[UniqueEntity('email')]
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

    public const TYPE_CLIENT = 'client';
    public const TYPE_EMPLOYEE = 'employee';
    public const TYPE_USER = 'user';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    protected ?int $id = null;

    #[ORM\Column(type: 'string', length: 80)]
    protected string $firstName;

    #[ORM\Column(type: 'string', length: 80)]
    protected string $lastName;

    #[ORM\Column(type: 'string', length: 80, nullable: true)]
    protected ?string $middleName = null;

    #[ORM\Column(type: "string", length: 180, unique: true)]
    protected string $email;

    #[ORM\Column(type: "string", length: 16, options: ['default' => Status::STATUS_ACTIVE->value])]
    protected string $status;

    #[ORM\Column(name: "passwordHash", type: "string", nullable: true)]
    protected ?string $passwordHash = null;

    #[ORM\Column(type: "json")]
    protected array $roles = [];

    protected function __construct(string $firstName, string $lastName, string $email, string $middleName = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;
        $this->email = $email;
        /*
         * todo replace to Status::WAIT->value
         * */
        $this->status = Status::STATUS_ACTIVE->value;
    }

    public static function create(
        string $firstName,
        string $lastName,
        string $email,
        string $passwordHash = null,
        string $middleName = null,
    ): self
    {
        $user = new self($firstName, $lastName, $email, $middleName);
        $user->passwordHash = $passwordHash;
//        $user->addRole(self::ROLE_ADMIN);
        return $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return self::TYPE_USER;
    }

    /**
     * @return string
     */
    public function getStatus(): string
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
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function isClient(string $subordinate): bool
    {
        return Subordinate::SUB_CLIENT->value === $subordinate;
    }

    public function isEmployee(string $subordinate): bool
    {
        return Subordinate::SUB_EMPLOYEE->value === $subordinate;
    }

    public function changeStatus(string $status): void
    {
        if ($this->status === $status) {
            throw new \DomainException('Status is already same.');
        }
        $this->status = $status;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
