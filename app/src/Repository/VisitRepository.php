<?php

namespace App\Repository;

use App\Entity\Visit;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;

class VisitRepository
{
    public EntityRepository $repo;

    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
        $this->repo = $this->em->getRepository(Visit::class);
    }

    public function save(Visit $entity): void
    {
        $this->em->persist($entity);
    }

    public function remove(Visit $entity): void
    {
        $this->em->remove($entity);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function get(int $id): Visit
    {
        /** @var Visit $visit */
        if (!$visit = $this->repo->find($id)) {
            throw new EntityNotFoundException('Посещение не найдено');
        }
        return $visit;
    }
}
