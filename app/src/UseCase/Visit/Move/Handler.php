<?php

namespace App\UseCase\Visit\Move;

use App\Entity\Client;
use App\Entity\Visit;
use App\Repository\ClientRepository;
use App\Repository\EmployeeRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use App\Repository\VisitRepository;
use App\Service\Doctrine\Flusher;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class Handler
{
    public function __construct(
        private readonly ServiceRepository $serviceRepository,
        private readonly EmployeeRepository $employeeRepository,
        private readonly ClientRepository $clientRepository,
        private readonly VisitRepository $visitRepository,
        private readonly Flusher $flusher,
    ) {
    }

    /**
     * @throws EntityNotFoundException
     * @throws \Exception
     */
    public function handle(Command $command): Visit
    {
        $visit = $this->visitRepository->get($command->id);
        $visit->setDateTime(new \DateTimeImmutable($command->date . ' '. $command->time));

        $this->visitRepository->save($visit);
        $this->flusher->flush();

        return $visit;
    }
}