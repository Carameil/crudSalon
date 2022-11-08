<?php

namespace App\Entity\User;

use App\Entity\Property\Email;
use App\Entity\Property\Id;
use App\Entity\Property\Role;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

abstract class AbstractedUser
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    protected Id $id;

    protected string $firstName;

    protected string $lastName;

    protected ?string $middleName = null;

    protected ?string $usernameCanonical = null;

    protected Email $email;

    protected ?string $emailCanonical = null;

    protected ?string $password = null;

    protected ?string $plainPassword = null;

    protected ?\DateTimeInterface $lastLogin = null;

    protected ?string $confirmationToken = null;

    protected ?\DateTimeInterface $passwordRequestedAt = null;

    //protected array $roles = [];

    protected ?\DateTimeInterface $createdAt = null;

    protected ?\DateTimeInterface $updatedAt = null;

    public function __toString(): string
    {
        return $this->getFullName();
    }

    /**
     * @return mixed[]
     */
    public function __serialize(): array
    {
        return [
                $this->password,
                $this->usernameCanonical,
                $this->firstName,
                $this->lastName,
                $this->middleName,
                $this->id,
                $this->email,
                $this->emailCanonical,
        ];
    }

    /**
     * @param mixed[] $data
     */
    public function __unserialize(array $data): void
    {
        [
                $this->password,
                $this->usernameCanonical,
                $this->firstName,
                $this->lastName,
                $this->middleName,
                $this->id,
                $this->email,
                $this->emailCanonical
        ] = $data;
    }

//    public function addRole(string $role): void
//    {
//        $role = strtoupper($role);
//
//        if ($role === static::ROLE_DEFAULT) {
//            return;
//        }
//
//        if (!\in_array($role, $this->roles, true)) {
//            $this->roles[] = $role;
//        }
//    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getFullName(): string
    {
        return implode(' ', array_filter([
                $this->lastName,
                $this->firstName,
                $this->middleName
        ]));
    }

    public function getUserIdentifier(): Email
    {
        return $this->email;
    }

    public function getUsernameCanonical(): ?string
    {
        return $this->usernameCanonical;
    }


    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getEmailCanonical(): ?string
    {
        return $this->emailCanonical;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

//    public function getRoles(): array
//    {
//        $roles = $this->roles;
//
//        // we need to make sure to have at least one role
//        $roles[] = static::ROLE_DEFAULT;
//
//        return array_values(array_unique($roles));
//    }

//    public function hasRole(string $role): bool
//    {
//        return \in_array(strtoupper($role), $this->getRoles(), true);
//    }

    public function isAccountNonExpired(): bool
    {
        return true;
    }

    public function isAccountNonLocked(): bool
    {
        return true;
    }

    public function isCredentialsNonExpired(): bool
    {
        return true;
    }

//    public function isSuperAdmin(): bool
//    {
//        return $this->hasRole(static::ROLE_SUPER_ADMIN);
//    }
//
//    public function removeRole(string $role): void
//    {
//        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
//            unset($this->roles[$key]);
//            $this->roles = array_values($this->roles);
//        }
//    }

    public function setUsernameCanonical(?string $usernameCanonical): void
    {
        $this->usernameCanonical = $usernameCanonical;
    }

    public function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    public function setEmailCanonical(?string $emailCanonical): void
    {
        $this->emailCanonical = $emailCanonical;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function setSuperAdmin(bool $boolean): void
    {
        if (true === $boolean) {
            $this->addRole(static::ROLE_SUPER_ADMIN);
        } else {
            $this->removeRole(static::ROLE_SUPER_ADMIN);
        }
    }

    public function setPlainPassword(?string $password): void
    {
        $this->plainPassword = $password;
    }

    public function setLastLogin(?\DateTimeInterface $time = null): void
    {
        $this->lastLogin = $time;
    }

    public function setConfirmationToken(?string $confirmationToken): void
    {
        $this->confirmationToken = $confirmationToken;
    }

    public function setPasswordRequestedAt(?\DateTimeInterface $date = null): void
    {
        $this->passwordRequestedAt = $date;
    }

    public function getPasswordRequestedAt(): ?\DateTimeInterface
    {
        return $this->passwordRequestedAt;
    }

    public function isPasswordRequestNonExpired(int $ttl): bool
    {
        $passwordRequestedAt = $this->getPasswordRequestedAt();

        return null !== $passwordRequestedAt && $passwordRequestedAt->getTimestamp() + $ttl > time();
    }

//    public function setRoles(array $roles): void
//    {
//        $this->roles = [];
//
//        foreach ($roles as $role) {
//            $this->addRole($role);
//        }
//    }

    public function isEqualTo(SymfonyUserInterface $user): bool
    {
        if (!$user instanceof self) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->email !== $user->getEmail()) {
            return false;
        }

        return true;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt = null): void
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt = null): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

//    public function getRealRoles(): array
//    {
//        return $this->roles;
//    }
//
//    public function setRealRoles(array $roles): void
//    {
//        $this->setRoles($roles);
//    }
}