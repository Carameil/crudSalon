<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;

class ClientRepository
{
    public EntityRepository $repo;

    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
        $this->repo = $this->em->getRepository(Client::class);
    }

    public function save(Client $entity): void
    {
        $this->em->persist($entity);
    }

    public function remove(Client $entity): void
    {
        $this->em->remove($entity);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function get($id): Client
    {
        /** @var Client $client */
        if (!$client = $this->repo->find($id)) {
            throw new EntityNotFoundException('Клиент не найден');
        }
        return $client;
    }
}
