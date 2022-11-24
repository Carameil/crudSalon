<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VisitController extends AbstractController
{
    #[Route('/visits', name: 'app_visit')]
    public function index(): Response
    {
        return $this->render('app/main/visit.html.twig', [
            'controller_name' => 'VisitController',
        ]);
    }
}
