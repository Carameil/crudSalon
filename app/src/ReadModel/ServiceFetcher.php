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
}