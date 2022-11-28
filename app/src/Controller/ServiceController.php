<?php

namespace App\Controller;

use App\ReadModel\CategoryFetcher;
use App\ReadModel\ServiceFetcher;
use App\Utils\MoneyHelper;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    public function __construct(
        private readonly ServiceFetcher $serviceFetcher,
        private readonly CategoryFetcher $categoryFetcher,
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route('/services', name: 'app_service')]
    public function index(Request $request): Response
    {
        $services = $this->serviceFetcher->findAll();
        $categories = $this->categoryFetcher->findAll();

        return $this->render('app/main/service.html.twig', [
            'services' => $services,
            'categories' => $categories,
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/services/listByCategory', name: 'app_service_by_category')]
    public function getList(Request $request): Response
    {
        $categoryId = $request->request->get('categoryId');

        if($categoryId) {
            $services = $this->serviceFetcher->findAllByCategoryId($categoryId);
        } else {
            $services = $this->serviceFetcher->findAll();
        }

        return $this->render('app/main/filters/_servicesByCategory.html.twig', [
            'services' => $services,
        ]);
    }
}
