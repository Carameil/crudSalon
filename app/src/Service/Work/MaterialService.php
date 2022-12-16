<?php

namespace App\Service\Work;


use App\Repository\MaterialRepository;
use App\Service\Doctrine\Flusher;
use Doctrine\ORM\EntityNotFoundException;

class MaterialService
{
    public function __construct(
        private readonly MaterialRepository $materialRepository,
        private readonly Flusher $flusher,
    )
    {
    }

    /**
     * @throws EntityNotFoundException
     */
    public function updateMaterialCount(int $materialId, int $quantity, string $unit): bool
    {
        $material = $this->materialRepository->get($materialId);

        if(!$material->checkUnit($unit)) {
            throw new \DomainException('Ошибка в выборе единицы измерения');
        }

        if($material->getQuantity() < $quantity) {
            throw new \DomainException('Расход больше, чем имеющееся количество');
        }

        $remainder = $material->getQuantity() - $quantity;

        $material->setQuantity($remainder);
        $this->materialRepository->save($material);
        $this->flusher->flush();

        return true;
    }
}