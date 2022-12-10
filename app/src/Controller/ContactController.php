<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contacts', name: 'app_contact')]
    public function index(): Response
    {
        return $this->render('app/main/contact.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
}
