<?php

namespace App\Controller\Admin\Crud;

use App\Admin\Filters\FullNameFilter;
use App\Admin\Filters\TypeFilter;
use App\Entity\User\Enum\Status;
use App\Entity\User\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use ReflectionClass;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    /**
     * @throws \ReflectionException
     */
    public function createEntity(string $entityFqcn)
    {
        return (new ReflectionClass(User::class))->newInstanceWithoutConstructor();
    }

    public function configureFields(string $pageName): iterable
    {

        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('firstName'),
            TextField::new('lastName'),
            TextField::new('middleName'),
            EmailField::new('email'),
            ChoiceField::new('status')->setChoices([
                'Ожидает подтверждения' => Status::STATUS_WAIT->value,
                'Активный' => Status::STATUS_ACTIVE->value,
                'Заблокирован' => Status::STATUS_BLOCKED->value
            ]),
            TextField::new('type')->setDisabled(),
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
        $filters
            ->add(ChoiceFilter::new('status')->setChoices([
                'Ожидает подтверждения' => Status::STATUS_WAIT->value,
                'Активный' => Status::STATUS_ACTIVE->value,
                'Заблокирован' => Status::STATUS_BLOCKED->value
            ]))
            ->add(TextFilter::new('email'))
            ->add(FullNameFilter::new('fullName'))
            ;

        if(!$fromChild) {
            $filters->add(TypeFilter::new('type'));
        }

        return $filters;
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    }

    private function hashPassword(): \Closure
    {
        return function($event) {
            $form = $event->getForm();
            if (!$form->isValid()) {
                return;
            }
            $password = $form->get('password')->getData();
            if ($password === null) {
                return;
            }

            $hash = $this->passwordHasher->hashPassword($this->getUser(), $password);
            $form->getData()->setPassword($hash);
        };
    }

}
