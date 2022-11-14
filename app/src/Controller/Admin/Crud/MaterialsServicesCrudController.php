<?php

namespace App\Controller\Admin\Crud;

use App\Entity\MaterialsServices;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MaterialsServicesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MaterialsServices::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
