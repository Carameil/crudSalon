<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Material;
use App\Entity\MaterialsServices;
use App\Entity\Position;
use App\Entity\Property\Enum\Unit;
use App\Entity\Service;
use App\Entity\Visit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Money\Money;

class AppFixtures extends Fixture
{
    public const SERVICE_REFERENCE = 'service-reference';

    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName('Маникюр');

        $manager->persist($category);

        $position = new Position();
        $position->setName('testPosition');
        $position->setSalary(2500000);

        $manager->persist($position);

        $material = new Material();
        $material->setName('testMaterial');
        $material->setDescription('qwe');
        $material->setManufacturer('OOO');
        $material->setSupplier('OAO');
        $material->setQuantity(5);

        $manager->persist($material);

        $service = Service::create(
            'Маникюр универсальный',
            $category,
            $position,
            250000,
            30,
            'qwe'
        );

        $manager->persist($service);
        $this->addReference(self::SERVICE_REFERENCE, $service);

        $materialServices = new MaterialsServices();
        $materialServices->setService($service);
        $materialServices->setMaterial($material);
        $materialServices->setQuantityMaterial(10);
        $materialServices->setUnit(Unit::MILLILITER);

        $manager->persist($materialServices);

        $manager->flush();

    }
}
