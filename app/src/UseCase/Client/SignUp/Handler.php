<?php

namespace App\UseCase\Client\SignUp;

use App\Entity\Client;
use App\Repository\UserRepository;
use App\Service\Doctrine\Flusher;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

readonly class Handler
{
    public function __construct(
        private UserRepository          $users,
        private PasswordHasherInterface $hasher,
        private Flusher                 $flusher,
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

        $client = Client::create(
            $command->firstName,
            $command->lastName,
            $email,
            $this->hasher->hash($command->plainPassword),
            $command->middleName
        );
        $client->setPhone($command->phone);

        $this->users->save($client);

        $this->flusher->flush();
    }
}