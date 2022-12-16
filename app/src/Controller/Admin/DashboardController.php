<?php

namespace App\Controller\Admin;


use App\Entity\Category;
use App\Entity\Client;
use App\Entity\Employee;
use App\Entity\Material;
use App\Entity\MaterialsServices;
use App\Entity\Position;
use App\Entity\Service;
use App\Entity\User\User;
use App\Entity\Visit;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{

    public function __construct()
    {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();
        return $this->render('app/admin/index.html.twig');
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Администрирование салона');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Главная', 'fa fa-dashboard');

        yield MenuItem::section('Персоналии');
        yield MenuItem::linkToCrud('Клиенты', 'fa-solid fa-person', Client::class);
        yield MenuItem::linkToCrud('Сотрудники', 'fa-solid fa-user-nurse', Employee::class);
        yield MenuItem::linkToCrud('Должности', 'fa-solid fa-image-portrait', Position::class);

        yield MenuItem::section('Рабочие кейсы');
        yield MenuItem::linkToCrud('Посещения', 'fa fa-eye', Visit::class);
        yield MenuItem::linkToCrud('Категории', 'fa fa-eye', Category::class);
        yield MenuItem::linkToCrud('Услуги', 'fa-solid fa-rectangle-list', Service::class);
        yield MenuItem::linkToCrud('Материалы', 'fa fa-truck-fast', Material::class);
        yield MenuItem::linkToCrud('Материалы Услуг', 'fa fa-recycle', MaterialsServices::class);

        yield MenuItem::section('Информация по пользователям');
        yield MenuItem::linkToCrud('Пользователи', 'fa fa-user', User::class);
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
