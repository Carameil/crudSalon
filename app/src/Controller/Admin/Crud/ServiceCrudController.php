<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Service;
use App\Service\FileUploader;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\UseCase\Admin\Import;

class ServiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Service::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Услуги')
            ->setEntityLabelInSingular('Услуга');
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

    #[Route('/services/import', name: 'app_services_import')]
    public function fillData(Request $request, Import\ServiceHandler $handler): Response
    {
        $command = new Import\Command();

        $form = $this->createForm(Import\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Услуги успешно импортированы');
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
