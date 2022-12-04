<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Service;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ServiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Service::class;
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name')->setLabel('Название услуги'),
            MoneyField::new('price')->setCurrency('RUB')->setLabel('Цена'),
            TextareaField::new('description')->setLabel('Описание'),
            IntegerField::new('avg_time')->setLabel('Время выполнения услуги')->setHelp('Указывать время только в Минутах'),
        ];
    }
}
