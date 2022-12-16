<?php

declare(strict_types=1);

namespace App\UseCase\Visit\Cancel;

use App\Repository\VisitRepository;
use App\Service\Doctrine\Flusher;
use Doctrine\ORM\EntityNotFoundException;

class Handler
{
    public function __construct(
        private readonly VisitRepository $visitRepository,
        private readonly Flusher $flusher,
    )
    {
    }

    /**
     * @throws EntityNotFoundException
     */
    public function handle(Command $command): void
    {
        $visit = $this->visitRepository->get($command->id);

        $visit->archive();

        $this->flusher->flush();
    }
}