<?php

namespace App\Controller\Widget;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WidgetController extends AbstractController
{
    public array $routes = [
        'home' => 'app_home',
        'works' => 'app_work',
        'services' => 'app_service',
        'contacts' => 'app_contact',
        'about' => 'app_about',
        'visits' => 'app_visit',
        'profile' => 'app_profile',
    ];

    public function navWidget(string $breadcrumb): Response
    {
        return $this->render('components/_nav.html.twig', [
            'routes' => $this->routes,
            'breadcrumb' => $breadcrumb
        ]);
    }
}