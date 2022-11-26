<?php

namespace App\Controller\Widget;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WidgetController extends AbstractController
{
    public array $routes = [
        'app_home' => 'Home',
        'app_work' => 'Works',
        'app_service' => 'Services',
        'app_contact' => 'Contacts',
        'app_about' => 'About',
        'app_visit' => 'Visits',
//        'app_profile' => 'Profile',
    ];

    public function navWidget(string $currentAction): Response
    {
        return $this->render('components/_nav.html.twig', [
            'routes' => $this->routes,
            'currentAction' => $currentAction
        ]);
    }
}