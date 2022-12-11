<?php

namespace App\ReadModel;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class ServiceFetcher
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * @throws Exception
     */
    public function findAll(): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select([
                's.id',
                's.name',
                's.description',
                's.price',
                'ctg.name as category',
            ])
            ->from('service', 's')
            ->innerJoin('s', 'category', 'ctg', 's.category_id = ctg.id')
            ->orderBy('s.name');

        return $stmt->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function findAllByCategoryId(?int $categoryId = null): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select([
                's.id',
                's.name',
                's.description',
                's.price',
                'ctg.name as category',
            ])
            ->from('service', 's')
            ->innerJoin('s', 'category', 'ctg', 's.category_id = ctg.id')
            ->andWhere('ctg.id = :categoryId')
            ->orderBy('s.name')
            ->setParameter('categoryId', $categoryId);

        return $stmt->fetchAllAssociative();

    }

    /**
     * @throws Exception
     */
    public function findAllByEmployeeId(int $employeeId): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select([
                's.id',
                's.name',
            ])
            ->from('service', 's')
            ->innerJoin('s', 'employee', 'e', 'e.position_id = s.position_id')
            ->where('e.id = :employeeId')
            ->orderBy('s.name')
            ->setParameter('employeeId', $employeeId);

        return $stmt->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function getById(int $serviceId): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select([
                's.id',
                's.name'
            ])
            ->from('service', 's')
            ->where('id = :id')
            ->orderBy('s.name')
            ->setParameter('id', $serviceId);

        return $stmt->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function getByVisitId(int $visitId): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select([
                's.id',
                's.name'
            ])
            ->from('service', 's')
            ->where('id = :id')
            ->orderBy('s.name')
            ->setParameter('id', $serviceId);

        return $stmt->fetchAllAssociative();
    }
}