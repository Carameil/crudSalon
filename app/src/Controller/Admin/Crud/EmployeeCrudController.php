<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Employee;
use App\Entity\User\AbstractedUser;
use App\Entity\User\Enum\Status;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use ReflectionClass;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EmployeeCrudController extends UserCrudController
{
    public static function getEntityFqcn(): string
    {
        return Employee::class;
    }

    /**
     * @throws \ReflectionException
     */
    public function createEntity(string $entityFqcn)
    {
        $client = (new ReflectionClass(Employee::class))->newInstanceWithoutConstructor();
        $client->addRole(AbstractedUser::ROLE_EMPLOYEE);
        $client->setStatus(Status::STATUS_WAIT);
        return $client;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('firstName'),
            TextField::new('lastName'),
            TextField::new('middleName'),
            AssociationField::new('position')
                ->setCrudController(PositionCrudController::class),
            TelephoneField::new('phone'),
            TextField::new('status')->onlyOnDetail(),
            EmailField::new('email'),
            TextField::new('password')
                ->setFormType(PasswordType::class)
                ->hideOnIndex()
                ->hideOnDetail(),
        ];
    }

    public function configureFilters(Filters $filters, ?bool $fromChild = false): Filters
    {
        $filters = parent::configureFilters($filters, true);
        $filters->add(EntityFilter::new('position'));
        return $filters;
    }

}
