<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/services', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('app/main/service.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }
}
