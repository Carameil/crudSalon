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
            mt.name as name
            ')
            ->from('material', 'mt')
            ->orderBy('mt.name');

        return $stmt->fetchAllAssociative();
    }
}