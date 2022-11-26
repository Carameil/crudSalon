<?php

namespace App\Repository;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class UserRepository
{
    public EntityRepository $repo;

    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
        $this->repo = $this->em->getRepository(User::class);
    }

    public function save(User $entity): void
    {
        $this->em->persist($entity);
    }

    public function remove(User $entity): void
    {
        $this->em->remove($entity);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function get($id): User
    {
        /** @var User $user */
        if (!$user = $this->repo->find($id)) {
            throw new EntityNotFoundException('Пользователь не найден');
        }
        return $user;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function getByEmail(string $email): User
    {
        /** @var User $user */
        if (!$user = $this->repo->findOneBy(['email' => $email])) {
            throw new EntityNotFoundException('Пользователь не найден');
        }

        return $user;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function hasByEmail(string $email): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.email = :email')
                ->setParameter(':email', $email)
                ->getQuery()->getSingleScalarResult() > 0;
    }
}
