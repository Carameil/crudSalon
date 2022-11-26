<?php

namespace App\UseCase\Client\SignUp;

use App\Entity\Client;
use App\Repository\UserRepository;
use App\Service\Doctrine\Flusher;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class Handler
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly Flusher $flusher,
    )
    {
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function handle(Command $command): void
    {
        $email = $command->email;

        if ($this->users->hasByEmail($email)) {
            throw new \DomainException('Пользователь с таким email существует');
        }

        $user = new Client(
            $command->firstName,
            $command->lastName,
            $email,
            $command->middleName
        );
        $user->setPassword($this->hasher->hashPassword($user, $command->plainPassword),);

        $this->users->save($user);

        $this->flusher->flush();
    }
}