<?php

namespace App\UseCase\Admin\Import;

use App\Entity\Client;
use App\Entity\Employee;
use App\Entity\Service;
use App\Entity\User\User;
use App\Repository\CategoryRepository;
use App\Repository\ClientRepository;
use App\Repository\EmployeeRepository;
use App\Repository\PositionRepository;
use App\Repository\ServiceRepository;
use App\Service\Doctrine\Flusher;
use App\Service\FileUploader;
use App\Utils\Parser\ParserInterface;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

readonly class ServiceHandler
{
    public function __construct(
        private FileUploader           $fileUploader,
        private ParserInterface        $parser,
        private Flusher                $flusher,
        private EntityManagerInterface $entityManager,
        private CategoryRepository     $categoryRepository,
        private PositionRepository     $positionRepository,
        private ServiceRepository      $serviceRepository,
    )
    {
    }

    /**
     * @throws Exception
     * @throws EntityNotFoundException
     */
    public function handle(Command $command)
    {
        $servicesFileName = $this->fileUploader->upload($command->uploadFile);
        $sheetData = $this->parser->parse($servicesFileName);

        $this->entityManager->getConnection()->beginTransaction();
        try {
            foreach ($sheetData as $row) {
                $service = Service::create(
                    $row['name'],
                    $this->categoryRepository->get($row['category_id']),
                    $this->positionRepository->get($row['position_id']),
                    $row['price'],
                    $row['avg_time'],
                    $row['description'],
                );

                $this->serviceRepository->save($service);
            }
            $this->flusher->flush();
            $this->entityManager->commit();
        } catch (Exception $exception) {
            $this->entityManager->rollback();
            throw $exception;
        }
        $this->fileUploader->deleteFile($servicesFileName);

        return true;
    }
}