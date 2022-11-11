<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Employee;
use App\Entity\Position;
use App\Entity\Property\Email;
use App\Entity\Property\Id;
use App\Entity\Property\Role;
use App\Entity\User\User;
use App\Repository\PositionRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly PositionRepository $positionRepository
    )
    {}

    public function load(ObjectManager $manager): void
    {

        $user = $this->createAdminByEmail(
            'admin',
            'admin',
            new Email('admin@admin.com'),
        );
        $user->setPassword($this->passwordHasher->hashPassword($user, 'admin'));
        $manager->persist($user);

        $client = $this->createClientByEmail(
            'client',
            'test',
            new Email('client@client.com'),
        );
        $client->setPassword($this->passwordHasher->hashPassword($client, 'client'));
        $client->setPhone('89258889966');
        $manager->persist($client);

        $employee = $this->createEmployeeByEmail(
            'employee',
            'test',
            new Email('employee@employee.com'),
        );
        $employee->setPassword($this->passwordHasher->hashPassword($employee, 'employee'));
        $employee->setPhone('89252223355');
        $employee->setPosition($this->positionRepository->findOneBy(['name' => 'testPosition']));
        $manager->persist($employee);

        $manager->flush();
    }

    public function createAdminByEmail(string $firstName, string $lastName, Email $email): User {
        return $this->createUserByEmail($firstName, $lastName, $email);
    }

    public function createClientByEmail(string $firstName, string $lastName, Email $email): Client {

        return new Client(
            $firstName,
            $lastName,
            $email
        );
    }

    public function createEmployeeByEmail(string $firstName, string $lastName, Email $email): Employee {

        return new Employee(
            $firstName,
            $lastName,
            $email
        );
    }

    public function createUserByEmail(string $firstName, string $lastName, Email $email): User {

        return User::create(
            $firstName,
            $lastName,
            $email,
        );
    }

    public function getDependencies()
    {
        return [
            AppFixtures::class,
        ];
    }
}