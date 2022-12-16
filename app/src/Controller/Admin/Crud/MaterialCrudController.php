<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Material;
use App\Entity\Property\Enum\Unit;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\UseCase\Admin\Import;
use Symfony\Component\Routing\Annotation\Route;

class MaterialCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Material::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Материалы')
            ->setEntityLabelInSingular('Материал');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name')->setLabel('Название'),
            TextField::new('manufacturer')->setLabel('Производитель'),
            TextareaField::new('description')->setLabel('Описание'),
            TextField::new('supplier')->setLabel('Поставщик'),
            IntegerField::new('quantity')->setLabel('Количество'),
            ChoiceField::new('unit')
                ->setLabel('Ед. измерения')
                ->setChoices([
                    'шт' => Unit::THING->value,
                    'мл' => Unit::MILLILITER->value,
                    'л' => Unit::LITER->value,
                    'уп' => Unit::PACKING->value,
                ])

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

    #[Route('/materials/import', name: 'app_materials_import')]
    public function fillData(Request $request, Import\MaterialHandler $handler): Response
    {
        $command = new Import\Command();

        $form = $this->createForm(Import\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Материалы успешно импортированы');
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
