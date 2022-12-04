<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;


class CategoryRepository
{
    public EntityRepository $repo;

    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
        $this->repo = $this->em->getRepository(Category::class);
    }

    public function save(Category $entity): void
    {
        $this->em->persist($entity);
    }

    public function remove(Category $entity): void
    {
        $this->em->remove($entity);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function get(int $id): Category
    {
        /** @var Category $category */
        if (!$category = $this->repo->find($id)) {
            throw new EntityNotFoundException('Категория не найдена');
        }
        return $category;
    }
}
