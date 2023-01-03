<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Client;
use App\Entity\User\AbstractedUser;
use App\Entity\User\Enum\Status;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use ReflectionClass;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ClientCrudController extends UserCrudController
{

    public static function getEntityFqcn(): string
    {
        return Client::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Клиенты')
            ->setEntityLabelInSingular('Клиент');
    }

    /**
     * @throws \ReflectionException
     */
    public function createEntity(string $entityFqcn)
    {
        $client = (new ReflectionClass(Client::class))->newInstanceWithoutConstructor();
        $client->addRole(AbstractedUser::ROLE_USER);
        $client->setStatus(Status::STATUS_ACTIVE->value);
        return $client;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('lastName')->setLabel('Фамилия'),
            TextField::new('firstName')->setLabel('Имя'),
            TextField::new('middleName')->setLabel('Отчество'),
            TelephoneField::new('phone')->setLabel('Телефон'),
            EmailField::new('email'),
            TextField::new('status')->onlyOnDetail()->setLabel('Статус'),
        ];

        $password = TextField::new('password')
            ->setLabel('Пароль')
            ->setFormType(RepeatedType::class)
            ->setFormTypeOptions([
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Пароль'],
                'second_options' => ['label' => 'Повторите пароль'],
                'mapped' => false,
            ])
            ->setRequired($pageName === Crud::PAGE_NEW)
            ->onlyOnForms();

        $fields[] = $password;

        return $fields;

    }

    public function configureFilters(Filters $filters, ?bool $fromChild = false): Filters
    {
        return parent::configureFilters($filters, true);
    }

    public function configureActions(Actions $actions, ?bool $fromChild = false): Actions
    {
        return parent::configureActions($actions, true);
    }

}
