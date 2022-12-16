<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Visit;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\UseCase\Admin\Import;

class VisitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Visit::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Посещения')
            ->setEntityLabelInSingular('Посещение');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('service')->setLabel('Услуга')
                ->setCrudController(ServiceCrudController::class),
            AssociationField::new('client')->setLabel('Клиент')
                ->setCrudController(ClientCrudController::class),
            AssociationField::new('employee')->setLabel('Сотрудник')
                ->setCrudController(EmployeeCrudController::class),
            DateTimeField::new('dateTime')->setLabel('Дата и время'),
        ];
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

    #[Route('/visits/import', name: 'app_visits_import')]
    public function fillData(Request $request, Import\VisitHandler $handler): Response
    {
        $command = new Import\Command();

        $form = $this->createForm(Import\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Посещения успешно импортированы');
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
