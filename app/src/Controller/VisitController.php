<?php

namespace App\Controller;

use App\ReadModel\CategoryFetcher;
use App\ReadModel\ServiceFetcher;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VisitController extends AbstractController
{
    public function __construct(
        private readonly ServiceFetcher $serviceFetcher,
        private readonly CategoryFetcher $categoryFetcher,
    )
    {
    }

    #[Route('/visits', name: 'app_visit')]
    public function index(): Response
    {
        return $this->render('app/main/visit.html.twig', [
            'controller_name' => 'VisitController',
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/book/{serviceId}', name: 'app_booking')]
    public function redirectToFormBooking(string $serviceId): Response
    {

        return $this->redirect($this->generateUrl('app_home', [
            '_fragment' => 'booking',
            'serviceId' => $serviceId,
        ]));
    }
}
