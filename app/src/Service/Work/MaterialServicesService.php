<?php

namespace App\Service\Work;


use App\Entity\MaterialsServices;
use App\Repository\MaterialRepository;
use App\Repository\MaterialsServicesRepository;
use App\Repository\ServiceRepository;
use App\Service\Doctrine\Flusher;
use Doctrine\ORM\EntityNotFoundException;

class MaterialServicesService
{
    public function __construct(
        private readonly MaterialsServicesRepository $materialsServicesRepository,
        private readonly MaterialRepository $materialRepository,
        private readonly ServiceRepository $serviceRepository,
        private readonly Flusher $flusher,
    )
    {
    }

    /**
     * @throws EntityNotFoundException
     */
    public function addInfoAboutClosedService(int $materialId, int $serviceId, int $quantity, string $unit): bool
    {
        try {
            $material = $this->materialRepository->get($materialId);
            $service = $this->serviceRepository->get($serviceId);

            $materialService = MaterialsServices::create($material, $service, $quantity, $unit);

            $this->materialsServicesRepository->save($materialService);
            $this->flusher->flush();
        } catch (\Exception $exception) {
            throw new $exception;
        }

        return true;
    }
}