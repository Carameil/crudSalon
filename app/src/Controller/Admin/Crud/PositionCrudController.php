<?php

namespace App\Controller\Admin\Crud;

use App\Admin\Field\MoneyField;
use App\Entity\Position;
use App\Repository\PositionRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class PositionCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Position::class;
    }

//    public function configureCrud(Crud $crud): Crud
//    {
//        return $crud
//            ->setFormThemes(['app/admin/field/money.html.twig', '@EasyAdmin/crud/form_theme.html.twig'])
//            ;
//    }

    public function configureFields(string $pageName): iterable
    {

        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            \EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField::new('salary')
                ->setCurrency('RUB')
//                ->setNumDecimals(2)
//                ->setFormTypeOptions([
//                    'block_name' => 'number_field',
//                ])

        ];
    }

}
