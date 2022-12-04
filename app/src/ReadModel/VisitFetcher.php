<?php

namespace App\ReadModel;

use App\Entity\Property\Enum\ServiceStatus;
use App\Entity\User\Enum\Status;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class VisitFetcher
{
    public const WORKING_HOURS = [
        'startTime' => '10:00',
        'endTime' => '20:00'
    ];

    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * @throws Exception
     */
    public function getOccupationEmployeeByDate(int $employeeId, string $date): array
    {
        $stmt = $this->connection->createQueryBuilder();
        $stmt->select(" 
                v.time::timestamp::time as time_from,
                (v.time::timestamp::time + (s.avg_time ||' minutes')::interval + '1 minutes' ) as time_to
        ")
            ->from('visit', 'v')
            ->innerJoin('v', 'employee', 'e', 'e.id = v.employee_id')
            ->innerJoin('v', 'service', 's', 's.id = v.service_id')
            ->andWhere('v.service_status = :status')
            ->andWhere('v.employee_id = :employeeId')
            ->andWhere('v.date::timestamp::date = :date')
            ->setParameter('status', ServiceStatus::ACTIVE)
            ->setParameter('date', $date)
            ->setParameter('employeeId', $employeeId);


        return $stmt->fetchAllAssociative();
    }
}