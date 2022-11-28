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
}