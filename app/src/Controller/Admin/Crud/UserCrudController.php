<?php

namespace App\Controller\Admin\Crud;

use App\Admin\Filters\FullNameFilter;
use App\Admin\Filters\TypeFilter;
use App\Entity\User\AbstractedUser;
use App\Entity\User\Enum\Status;
use App\Entity\User\User;
use App\Service\FileUploader;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\UseCase\Admin\Import;

class UserCrudController extends AbstractCrudController
{

    public function __construct(private readonly PasswordHasherInterface $passwordHasher)
    {
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Пользователи')
            ->setEntityLabelInSingular('Пользователь');
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
            TextField::new('lastName')->setLabel('Фамилия'),
            TextField::new('firstName')->setLabel('Имя'),
            TextField::new('middleName')->setLabel('Отчество'),
            EmailField::new('email'),
            ChoiceField::new('status')->setChoices([
                'Ожидает подтверждения' => Status::STATUS_WAIT->value,
                'Активный' => Status::STATUS_ACTIVE->value,
                'Заблокирован' => Status::STATUS_BLOCKED->value
            ])->setLabel('Статус'),
            TextField::new('type')->setDisabled()->setLabel('Тип'),
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
        $filters
            ->add(ChoiceFilter::new('status')
                ->setLabel('Статус')
                ->setChoices([
                'Ожидает подтверждения' => Status::STATUS_WAIT->value,
                'Активный' => Status::STATUS_ACTIVE->value,
                'Заблокирован' => Status::STATUS_BLOCKED->value
            ]))
            ->add(TextFilter::new('email'))
            ->add(FullNameFilter::new('fullName')->setLabel('ФИО')
            );

        if(!$fromChild) {
            $filters->add(TypeFilter::new('type')->setLabel('Тип'));
        }

        return $filters;
    }

    public function configureActions(Actions $actions): Actions
    {
        $fillDataAction = Action::new('Импорт')
            ->linkToCrudAction('fillData')
            ->setTemplatePath('app/admin/action.html.twig')
            ->addCssClass('btn btn-primary')
            ->setIcon('fa-solid fa-file-import')
            ->createAsGlobalAction()
            ->displayAsButton();
        return parent::configureActions($actions)
            ->add(Crud::PAGE_INDEX, $fillDataAction);
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

            $hash = $this->passwordHasher->hash($password);
            $form->getData()->setPassword($hash);
        };
    }

    #[Route('/users/import', name: 'app_users_import')]
    public function fillData(Request $request, Import\UserHandler $handler): Response
    {
        $command = new Import\Command();

        $form = $this->createForm(Import\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Пользователи успешно импортированы');
                return $this->redirect($this->generateUrl('admin', [
                    '_fragment' => 'booking',
                ]));
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/admin/import.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
