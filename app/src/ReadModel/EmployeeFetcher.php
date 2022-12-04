<?php

namespace App\ReadModel;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class EmployeeFetcher
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
            ->select("e.id, CONCAT_WS(' ', u.last_name, u.first_name, u.middle_name) as fullName")
            ->from('employee', 'e')
            ->innerJoin('e', '"user"', 'u', 'e.id = u.id')
            ->orderBy('fullName');

        return $stmt->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function findAllByServiceId(int $serviceId): array
    {
        $stmt = $this->connection->createQueryBuilder();

        $stmt->select("e.id, CONCAT_WS(' ', u.last_name, u.first_name, u.middle_name) as fullName")
            ->from('employee', 'e')
            ->innerJoin('e', '"user"', 'u', 'e.id = u.id')
            ->innerJoin('e', '"service"', 's', 'e.position_id = s.position_id')
            ->where('s.id = :serviceId')
            ->orderBy('fullName')
            ->setParameter('serviceId', $serviceId);

        return $stmt->fetchAllAssociative();
    }
}