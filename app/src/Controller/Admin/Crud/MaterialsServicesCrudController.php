<?php

namespace App\Controller\Admin\Crud;

use App\Entity\MaterialsServices;
use App\Entity\Property\Enum\Unit;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
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

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Материалы услуг')
            ->setEntityLabelInSingular('Материал услуги');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('service')->setLabel('Услуга')
                ->setCrudController(ServiceCrudController::class),
            AssociationField::new('material')->setLabel('Материал')
                ->setCrudController(MaterialCrudController::class),
            IntegerField::new('quantity_material')->setLabel('Количество затрат'),
            ChoiceField::new('unit')->setLabel('Ед. измерения')
                ->setChoices([
                    'шт' => Unit::THING->value,
                    'мл' => Unit::MILLILITER->value,
                    'л' => Unit::LITER->value,
                    'уп' => Unit::PACKING->value,
                ])
        ];
    }
}
