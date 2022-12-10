<?php

namespace App\ReadModel\Visit;

use App\Entity\Property\Enum\ServiceStatus;
use App\ReadModel\Visit\Filter\Filter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class VisitFetcher
{
    public const WORKING_HOURS = [
        'startTime' => '10:00',
        'endTime' => '20:00'
    ];

    public function __construct(
        private readonly Connection $connection,
        private readonly PaginatorInterface $paginator
    )
    {
    }

//    /**
//     * @throws Exception
//     */
//    public function getVisitsByServiceId(int $serviceId)
//    {
//        $stmt = $this->connection->createQueryBuilder();
//        $stmt->select("
//            CONCAT_WS(' ', u.last_name, u.first_name, u.middle_name) as full_name,
//            s.name as service_name,
//            v.date::timestamp::date as date,
//            v.time::timestamp::time as time
//            ")
//            ->from('visit', 'v')
//            ->innerJoin('v', 'service', 's', 's.id = v.service_id')
//            ->innerJoin('v', '"user"', 'u', 'v.client_id = u.id')
//            ->where('v.employee_id = :employeeId')
//            ->setParameter('employeeId', $employeeId);
//
//        return $stmt->fetchAllAssociative();
//    }

    /**
     * @throws Exception
     */
    public function getOccupationEmployeeByDate(int $employeeId, string $date): array
    {
        $stmt = $this->connection->createQueryBuilder();
        $stmt->select(" 
                v.date_time::timestamp::time as time_from,
                (v.date_time::timestamp::time + (s.avg_time ||' minutes')::interval + '1 minutes' ) as time_to
        ")
            ->from('visit', 'v')
            ->innerJoin('v', 'employee', 'e', 'e.id = v.employee_id')
            ->innerJoin('v', 'service', 's', 's.id = v.service_id')
            ->andWhere('v.service_status = :status')
            ->andWhere('v.employee_id = :employeeId')
            ->andWhere('v.date_time::timestamp::date = :date')
            ->setParameter('status', ServiceStatus::ACTIVE)
            ->setParameter('date', $date)
            ->setParameter('employeeId', $employeeId);


        return $stmt->fetchAllAssociative();
    }

    public function getActiveRecordsByEmployeeId(int $employeeId, Filter $filter, int $page, int $size, string $sort, string $direction): PaginationInterface
    {
        $stmt = $this->connection->createQueryBuilder();
        $stmt->select("
        v.id,
        CONCAT_WS(' ', u.last_name, u.first_name, u.middle_name) as full_name,
        s.name as service_name,
        v.date_time as date_time
        ")
            ->from('visit', 'v')
            ->innerJoin('v', 'service', 's', 's.id = v.service_id')
            ->innerJoin('v', '"user"', 'u', 'v.client_id = u.id')
            ->andWhere('v.employee_id = :employeeId')
            ->setParameter('employeeId', $employeeId);

        $this->fetchFilter($filter, $stmt);

        if (!\in_array($sort, ['fullName', 'service', 'date_time'], true)) {
            throw new \UnexpectedValueException('Невозможно отсортировать по полю ' . $sort);
        }

        $stmt->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');

        return $this->paginator->paginate($stmt, $page, $size);
    }

    public function getActiveRecordsByClientId(int $clientId, Filter $filter, int $page, int $size, string $sort, string $direction): PaginationInterface
    {
        $stmt = $this->connection->createQueryBuilder();
        $stmt->select("
        v.id,
        CONCAT_WS(' ', u.last_name, u.first_name, u.middle_name) as full_name,
        s.name as service_name,
        v.date_time as date_time
        ")
            ->from('visit', 'v')
            ->innerJoin('v', 'service', 's', 's.id = v.service_id')
            ->innerJoin('v', '"user"', 'u', 'v.employee_id = u.id')
            ->andWhere('v.client_id = :clientId')
            ->setParameter('clientId', $clientId);

        $this->fetchFilter($filter, $stmt);

        if (!\in_array($sort, ['fullName', 'service', 'date_time'], true)) {
            throw new \UnexpectedValueException('Невозможно отсортировать по полю ' . $sort);
        }

        $stmt->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');

        return $this->paginator->paginate($stmt, $page, $size);
    }

    private function fetchFilter(Filter $filter, QueryBuilder $stmt, ): void
    {
        if ($filter->fullName) {
            $stmt->andWhere($stmt->expr()->like('LOWER(CONCAT(last_name, \' \', first_name, \' \', middle_name ))', ':fullName'));
            $stmt->setParameter('fullName', '%' . mb_strtolower($filter->fullName) . '%');
        }

        if ($filter->service) {
            $stmt->andWhere($stmt->expr()->like('LOWER(s.name)', ':serviceName'));
            $stmt->setParameter('serviceName', '%' . mb_strtolower($filter->service) . '%');
        }

        if ($filter->dateTime) {
            $stmt->andWhere($stmt->expr()->eq("v.date_time", ':dateTime'));
            $stmt->setParameter('dateTime', $filter->dateTime->format('Y-m-d H:i:s'));
        }
    }
}