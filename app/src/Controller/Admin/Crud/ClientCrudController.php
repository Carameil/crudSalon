<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Client;
use App\Entity\User\AbstractedUser;
use App\Entity\User\Enum\Status;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use ReflectionClass;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientCrudController extends UserCrudController
{

    public static function getEntityFqcn(): string
    {
        return Client::class;
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
            TextField::new('lastName'),
            TextField::new('firstName'),
            TextField::new('middleName'),
            TelephoneField::new('phone'),
            EmailField::new('email'),
            TextField::new('status')->onlyOnDetail(),
        ];

        $password = TextField::new('password')
            ->setFormType(RepeatedType::class)
            ->setFormTypeOptions([
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => '(Repeat)'],
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

}
