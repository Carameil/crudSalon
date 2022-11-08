<?php

namespace App\Entity\Property;

use Webmozart\Assert\Assert;

class Role
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    private string $name;

    public function __construct(string $name)
    {
        Assert::oneOf($name, [
                self::ROLE_USER,
                self::ROLE_ADMIN,
        ]);

        $this->name = $name;
    }

    public static function user(): self
    {
        return new self(self::ROLE_USER);
    }

    public static function admin(): self
    {
        return new self(self::ROLE_ADMIN);
    }

    public function isUser(): bool
    {
        return $this->name === self::ROLE_USER;
    }

    public function isManager(): bool
    {
        return $this->name === self::ROLE_ADMIN;
    }

    public function isEqual(self $role): bool
    {
        return $this->getName() === $role->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }
}