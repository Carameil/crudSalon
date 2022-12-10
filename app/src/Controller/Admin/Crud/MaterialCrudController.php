<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Material;
use App\Entity\Property\Enum\Unit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MaterialCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Material::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextField::new('manufacturer'),
            TextareaField::new('description'),
            TextField::new('supplier'),
            IntegerField::new('quantity'),
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
