<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Visit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class VisitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Visit::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('service')
                ->setCrudController(ServiceCrudController::class),
            AssociationField::new('client')
                ->setCrudController(ClientCrudController::class),
            AssociationField::new('employee')
                ->setCrudController(EmployeeCrudController::class),
            DateTimeField::new('dateTime'),
        ];
    }

}
