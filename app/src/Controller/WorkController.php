<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorkController extends AbstractController
{
    #[Route('/works', name: 'app_work')]
    public function index(): Response
    {
        return $this->render('app/main/work.html.twig', [
            'controller_name' => 'WorkController',
        ]);
    }
}
