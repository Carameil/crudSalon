<?php

namespace App\ReadModel;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class MaterialFetcher
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
            ->select('
            mt.id as id,
            mt.name as name,
            mt.unit
            ')
            ->from('material', 'mt')
            ->orderBy('mt.name');

        return $stmt->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function findUnitByMaterialId(int $materialId)
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select('
            mt.unit as unit,
            ')
            ->from('material', 'mt')
            ->where('mt.id =: materialId')
            ->setParameter('materialId', $materialId);

        return $stmt->fetchOne();
    }
}