<?php

namespace App\Repository;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository implements UserLoaderInterface
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

    public function findByEmail(string $email): ?User
    {
        return $this->repo->findOneBy(['email' => $email]);
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

    /**
     * @throws NonUniqueResultException
     */
    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        return $this->em->createQuery(
            'SELECT u
                FROM App\Entity\User\User u
                WHERE u.email = :query'
        )
            ->setParameter('query', $identifier)
            ->getOneOrNullResult();
    }
}
