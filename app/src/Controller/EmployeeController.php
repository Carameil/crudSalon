<?php

namespace App\Controller;

use App\ReadModel\EmployeeFetcher;
use App\ReadModel\ServiceFetcher;
use App\ReadModel\Visit\VisitFetcher;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    public function __construct(
        private readonly EmployeeFetcher $employeeFetcher,
        private readonly ServiceFetcher $serviceFetcher,
        private readonly VisitFetcher $visitFetcher,
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route('/employees/selectorByService', name: 'app_employees_selector_by_service')]
    public function getSelectorByService(Request $request): Response
    {
        $serviceId = $request->request->get('serviceId');

        $employees = $this->employeeFetcher->findAllByServiceId($serviceId);
        $serviceName = $this->serviceFetcher->getById($serviceId);

        return $this->render('app/main/filters/_employeesSelectorByService.html.twig', [
            'employees' => $employees,
            'serviceName' => $serviceName,
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/employees/occupation', name: 'app_employee_occupation')]
    public function getOccupation(Request $request): Response
    {
        $employeeId = $request->request->get('employeeId');
        $date = $request->request->get('date');

        $occupation = $this->visitFetcher->getOccupationEmployeeByDate($employeeId, $date);
        $workingHours = $this->visitFetcher::WORKING_HOURS;

        return new JsonResponse([
            'occupation' => $occupation,
            'workingHours' => $workingHours
        ]);
    }
}
