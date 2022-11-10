<?php

namespace App\DataFixtures;

use App\Entity\Position;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $position = new Position();
        $position->setName('testPosition');
        $position->setSalary(35000);

        $manager->persist($position);
        $manager->flush();

    }
}
