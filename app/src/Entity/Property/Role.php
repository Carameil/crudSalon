<?php

namespace App\Entity\Property;

use Webmozart\Assert\Assert;

class Role
{
    public const USER = 'ROLE_USER';
    public const MANAGER = 'ROLE_MANAGER';

    private string $name;

    public function __construct(string $name)
    {
        Assert::oneOf($name, [
                self::USER,
                self::MANAGER,
        ]);

        $this->name = $name;
    }

    public static function user(): self
    {
        return new self(self::USER);
    }

    public static function admin(): self
    {
        return new self(self::MANAGER);
    }

    public function isUser(): bool
    {
        return $this->name === self::USER;
    }

    public function isManager(): bool
    {
        return $this->name === self::MANAGER;
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