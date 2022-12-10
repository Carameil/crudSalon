<?php

namespace App\Controller\Widget;

use App\Entity\User\AbstractedUser;
use App\Entity\User\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WidgetController extends AbstractController
{
    public array $routes = [
        'app_home' => 'Главная',
        'app_work' => 'Работы',
        'app_service' => 'Услуги',
        'app_contact' => 'Контакты',
        'app_about' => 'О нас',
        'app_employee_records' => 'Активные записи',
        'app_client_records' => 'Мои записи',
        'app_logout' => 'Выйти',
//        'app_profile' => 'Profile',
    ];

    public function navWidget(string $currentAction): Response
    {
        if (!in_array(AbstractedUser::ROLE_EMPLOYEE, $this->getUser()?->getRoles(), true)) {
            unset($this->routes['app_employee_records']);
        } else {
            unset($this->routes['app_client_records']);
        }

        return $this->render('components/_nav.html.twig', [
            'routes' => $this->routes,
            'currentAction' => $currentAction
        ]);
    }
}