<?php

namespace App\Controller;

use App\ReadModel\CategoryFetcher;
use App\ReadModel\EmployeeFetcher;
use App\ReadModel\ServiceFetcher;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\Translation\t;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly ServiceFetcher $serviceFetcher,
        private readonly CategoryFetcher $categoryFetcher,
        private readonly EmployeeFetcher $employeeFetcher,
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route('/home/{serviceId}', name: 'app_home')]
    public function index(?string $serviceId = null): Response
    {
        $services = $this->serviceFetcher->findAll();
        $categories = $this->categoryFetcher->findAll();
        $employees = $this->employeeFetcher->findAll();

        $bookCategory = $this->categoryFetcher->getByServiceId((int)$serviceId);
        $bookService = $this->serviceFetcher->getById((int)$serviceId);
        $employeesByBookService = $this->employeeFetcher->findAllByServiceId((int)$serviceId);

        return $this->render('app/index.html.twig', [
            'services' => $services,
            'categories' => $categories,
            'employees' => $employees,
            'bookService' => $bookService,
            'bookCategory' => $bookCategory,
            'employeesByBookService' => $employeesByBookService,
        ]);
    }
}
