<?php

namespace App\Repository;

use App\Entity\MaterialsServices;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MaterialsServicesRepository
{
    public EntityRepository $repo;

    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
        $this->repo = $this->em->getRepository(MaterialsServices::class);
    }

    public function save(MaterialsServices $entity): void
    {
        $this->em->persist($entity);
    }

    public function remove(MaterialsServices $entity): void
    {
        $this->em->remove($entity);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function get($id): MaterialsServices
    {
        /** @var MaterialsServices $materialService */
        if (!$materialService = $this->repo->find($id)) {
            throw new EntityNotFoundException('Материал не найден');
        }
        return $materialService;
    }
}
