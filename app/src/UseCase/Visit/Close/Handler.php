<?php

namespace App\UseCase\Visit\Close;

use App\Entity\Property\Enum\ServiceStatus;
use App\Entity\Visit;
use App\Repository\VisitRepository;
use App\Service\Doctrine\Flusher;
use App\Service\Work\MaterialService;
use App\Service\Work\MaterialServicesService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Exception;

readonly class Handler
{
    public function __construct(
        private MaterialService         $materialService,
        private MaterialServicesService $materialServicesService,
        private VisitRepository         $visitRepository,
        private EntityManagerInterface  $entityManager,
        private Flusher                 $flusher,
    ) {
    }

    /**
     * @throws EntityNotFoundException
     * @throws \Exception
     * @throws Exception
     */
    public function handle(Command $command): Visit
    {
        $visit = $this->visitRepository->get($command->id);
        $serviceId = $visit->getService()->getId();

        $this->entityManager->getConnection()->beginTransaction();
        try {
            foreach ($command->materials as $material) {
                $this->materialService->updateMaterialCount(
                    $material->materialId,
                    $material->quantity,
                    $material->unit
                );

                $this->materialServicesService->addInfoAboutClosedService(
                    $material->materialId,
                    $serviceId,
                    $material->quantity,
                    $material->unit
                );
            }

            $visit->setServiceStatus(ServiceStatus::CLOSED->value);
            $this->visitRepository->save($visit);
            $this->flusher->flush();
            $this->entityManager->commit();
        } catch (Exception $exception) {
            $this->entityManager->rollback();
            throw $exception;
        }

        return $visit;
    }
}