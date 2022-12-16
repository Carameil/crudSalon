<?php

namespace App\UseCase\Admin\Import;

use App\Entity\Client;
use App\Entity\Employee;
use App\Entity\User\User;
use App\Repository\ClientRepository;
use App\Repository\EmployeeRepository;
use App\Repository\PositionRepository;
use App\Service\Doctrine\Flusher;
use App\Service\FileUploader;
use App\Utils\Parser\ParserInterface;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

readonly class UserHandler
{
    public function __construct(
        private FileUploader    $fileUploader,
        private ParserInterface $parser,
        private Flusher $flusher,
        private EntityManagerInterface $entityManager,
        private EmployeeRepository $employeeRepository,
        private ClientRepository $clientRepository,
        private PositionRepository $positionRepository,
        private PasswordHasherInterface $passwordHasher,
    )
    {
    }

    /**
     * @throws Exception
     * @throws EntityNotFoundException
     */
    public function handle(Command $command)
    {
        $usersFileName = $this->fileUploader->upload($command->uploadFile);
        $sheetData = $this->parser->parse($usersFileName);

        $this->entityManager->getConnection()->beginTransaction();
        try {
            foreach ($sheetData as $row) {
                if(($row['type'] === User::TYPE_EMPLOYEE)){
                    $employee = Employee::create(
                        $row['first_name'],
                        $row['last_name'],
                        $row['email'],
                        $this->passwordHasher->hash($row['plainPassword']),
                        $row['middle_name'],
                    );
                    $employee->setPhone($row['phone']);
                    $employee->setAddress($row['address']);
                    $employee->setPosition($this->positionRepository->get($row['position_id']));

                    $this->employeeRepository->save($employee);
                } elseif ($row['type'] === User::TYPE_CLIENT){
                    $client = Client::create(
                        $row['first_name'],
                        $row['last_name'],
                        $row['email'],
                        $this->passwordHasher->hash($row['plainPassword']),
                        $row['middle_name'],
                    );
                    $client->setPhone($row['phone']);

                    $this->clientRepository->save($client);
                }
            }
            $this->flusher->flush();
            $this->entityManager->commit();

        } catch (EntityNotFoundException $exception) {
            $this->entityManager->rollback();
            throw $exception;
        }
        $this->fileUploader->deleteFile($usersFileName);

        return true;
    }
}