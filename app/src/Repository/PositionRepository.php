<?php

namespace App\Repository;

use App\Entity\Position;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class PositionRepository
{
    public EntityRepository $repo;

    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
        $this->repo = $this->em->getRepository(Position::class);
    }

    public function save(Position $entity): void
    {
        $this->em->persist($entity);
    }

    public function remove(Position $entity): void
    {
        $this->em->remove($entity);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function get(int $id): Position
    {
        /** @var Position $position */
        if (!$position = $this->repo->find($id)) {
            throw new EntityNotFoundException('Должность не найдена');
        }
        return $position;
    }
}
