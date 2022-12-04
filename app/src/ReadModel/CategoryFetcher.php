<?php

namespace App\ReadModel;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class CategoryFetcher
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
            ->select('*')
            ->from('category', 'ctg')
            ->orderBy('ctg.name');

        return $stmt->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function getByServiceId(int $serviceId): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select([
                'ctg.id',
                'ctg.name'
            ])
            ->from('category', 'ctg')
            ->innerJoin('ctg', '"service"', 's', 's.category_id = ctg.id')
            ->where('s.id = :serviceId')
            ->orderBy('ctg.name')
            ->setParameter('serviceId', $serviceId);

        return $stmt->fetchAllAssociative();
    }
}