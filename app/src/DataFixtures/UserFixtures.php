<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Employee;
use App\Entity\Service;
use App\Entity\User\User;
use App\Entity\Visit;
use App\Repository\PositionRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly PositionRepository          $positionRepository
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = $this->createAdminByEmail(
            'admin',
            'admin',
            'admin@admin.com',
        );
        $user->setPassword($this->passwordHasher->hashPassword($user, 'admin'));
        $manager->persist($user);

        $client = $this->createClientByEmail(
            'client',
            'test',
            'client@client.com',
            '89258889966'
        );
        $client->setPassword($this->passwordHasher->hashPassword($client, 'client'));
        $manager->persist($client);

        $employee = $this->createEmployeeByEmail(
            'employee',
            'test',
            'employee@employee.com',
            '89252223355'
        );
        $employee->setPassword($this->passwordHasher->hashPassword($employee, 'employee'));
        $employee->setPosition($this->positionRepository->findOneBy(['name' => 'testPosition']));
        $manager->persist($employee);

        /** @var Service $service */
        $service = $this->getReference(AppFixtures::SERVICE_REFERENCE);
        $visit = $this->createVisit(
            $service,
            $employee,
            $client,
            new \DateTimeImmutable('2022-11-18'),
            new \DateTimeImmutable('15:00:00')
        );
        $manager->persist($visit);

        $manager->flush();
    }

    public function createAdminByEmail(string $firstName, string $lastName, string $email): User
    {
        return $this->createUserByEmail($firstName, $lastName, $email);
    }

    public function createClientByEmail(string $firstName, string $lastName, string $email, string $phone): Client
    {

        return new Client(
            $firstName,
            $lastName,
            $email,
            $phone
        );
    }

    public function createEmployeeByEmail(string $firstName, string $lastName, string $email, string $phone): Employee
    {

        return new Employee(
            $firstName,
            $lastName,
            $email,
            $phone
        );
    }

    public function createUserByEmail(string $firstName, string $lastName, string $email): User
    {

        return User::create(
            $firstName,
            $lastName,
            $email,
        );
    }

    public function createVisit(
        Service $service,
        Employee $employee,
        Client $client,
        \DateTimeInterface $date,
        \DateTimeInterface $time
    ): Visit
    {
        return Visit::create(
            $service,
            $employee,
            $client,
            $date,
            $time
        );
    }

    public function getDependencies(): array
    {
        return [
            AppFixtures::class,
        ];
    }
}