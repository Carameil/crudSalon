<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;

class EmployeeRepository
{
    public EntityRepository $repo;

    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
        $this->repo = $this->em->getRepository(Employee::class);
    }

    public function save(Employee $entity): void
    {
        $this->em->persist($entity);
    }

    public function remove(Employee $entity): void
    {
        $this->em->remove($entity);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function get($id): Employee
    {
        /** @var Employee $employee */
        if (!$employee = $this->repo->find($id)) {
            throw new EntityNotFoundException('Сотрудник не найден');
        }
        return $employee;
    }
}
