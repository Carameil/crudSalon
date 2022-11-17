<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Client;
use App\Entity\User\AbstractedUser;
use App\Entity\User\Enum\Status;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use ReflectionClass;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ClientCrudController extends AbstractCrudController
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
        $client->setStatus(Status::STATUS_ACTIVE);
        return $client;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('firstName'),
            TextField::new('lastName'),
            TextField::new('middleName'),
            TextField::new('phone'),
            EmailField::new('email'),
            TextField::new('status')->onlyOnDetail(),
            TextField::new('password')
                ->setFormType(PasswordType::class)
                ->hideOnIndex()
                ->hideOnDetail(),
        ];

    }

}
