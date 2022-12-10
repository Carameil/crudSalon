<?php

namespace App\Controller\Admin\Crud;

use App\Entity\MaterialsServices;
use App\Entity\Property\Enum\Unit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class MaterialsServicesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MaterialsServices::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('service')
                ->setCrudController(ServiceCrudController::class),
            AssociationField::new('material')
                ->setCrudController(MaterialCrudController::class),
            IntegerField::new('quantity_material'),
            ChoiceField::new('unit')
                ->setChoices([
                    'шт' => Unit::THING,
                    'мл' => Unit::MILLILITER,
                    'л' => Unit::LITER,
                    'уп' => Unit::PACKING,
                ])
        ];
    }
}
