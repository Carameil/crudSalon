<?php

namespace App\UseCase\Admin\Import;

use App\Entity\Visit;
use App\Repository\ClientRepository;
use App\Repository\EmployeeRepository;
use App\Repository\ServiceRepository;
use App\Repository\VisitRepository;
use App\Service\Doctrine\Flusher;
use App\Service\FileUploader;
use App\Utils\Parser\ParserInterface;
use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

readonly class VisitHandler
{
    public function __construct(
        private FileUploader           $fileUploader,
        private ParserInterface        $parser,
        private Flusher                $flusher,
        private EntityManagerInterface $entityManager,
        private EmployeeRepository     $employeeRepository,
        private ClientRepository       $clientRepository,
        private ServiceRepository      $serviceRepository,
        private VisitRepository        $visitRepository,
    )
    {
    }

    /**
     * @throws Exception
     * @throws EntityNotFoundException
     */
    public function handle(Command $command)
    {
        $visitsFileName = $this->fileUploader->upload($command->uploadFile);
        $sheetData = $this->parser->parse($visitsFileName);

        $this->entityManager->getConnection()->beginTransaction();
        try {
            foreach ($sheetData as $row) {
                $visit = Visit::create(
                    $this->serviceRepository->get($row['service_id']),
                    $this->employeeRepository->get($row['employee_id']),
                    $this->clientRepository->get($row['client_id']),
                    new DateTimeImmutable($row['date'] . ' ' . $row['time']),
                );

                $this->visitRepository->save($visit);
            }
            $this->flusher->flush();
            $this->entityManager->commit();
        } catch (\Exception $exception) {
            $this->entityManager->rollback();
            throw $exception;
        }
        $this->fileUploader->deleteFile($visitsFileName);

        return true;
    }
}