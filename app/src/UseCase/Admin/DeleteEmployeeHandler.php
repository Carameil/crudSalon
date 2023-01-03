<?php

namespace App\UseCase\Admin;

use App\Entity\User\Enum\Status;
use App\Repository\UserRepository;
use App\Service\Doctrine\Flusher;
use Doctrine\ORM\EntityNotFoundException;
use Exception;

readonly class DeleteEmployeeHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private Flusher $flusher,
    )
    {

    }

    /**
     * @throws EntityNotFoundException
     */
    public function handle(int $employeeId): true
    {
        $employee = $this->userRepository->get($employeeId);
        $employee->remove();

        $this->userRepository->save($employee);
        $this->flusher->flush();
        //добавить логику, при удалении сотрудника - отменять его записи

        return true;
    }
}