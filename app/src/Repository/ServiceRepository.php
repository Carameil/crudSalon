<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;

class ServiceRepository
{
    public EntityRepository $repo;

    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
        $this->repo = $this->em->getRepository(Service::class);
    }

    public function save(Service $entity): void
    {
        $this->em->persist($entity);
    }

    public function remove(Service $entity): void
    {
        $this->em->remove($entity);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function get(int $id): Service
    {
        /** @var Service $service */
        if (!$service = $this->repo->find($id)) {
            throw new EntityNotFoundException('Услуга не найдена');
        }
        return $service;
    }
}
