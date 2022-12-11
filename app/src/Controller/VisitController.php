<?php

namespace App\Controller;

use App\Entity\User\User;
use App\Entity\Visit;
use App\ReadModel\CategoryFetcher;
use App\ReadModel\MaterialFetcher;
use App\ReadModel\ServiceFetcher;
use App\ReadModel\Visit\VisitFetcher;
use App\ReadModel\Visit\Filter;
use App\UseCase\Visit\Create;
use App\UseCase\Visit\Move;
use App\UseCase\Visit\Close;
use App\UseCase\Visit\Cancel;
use Doctrine\DBAL\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VisitController extends AbstractController
{
    private const PER_PAGE = 30;

    public function __construct(
        private readonly ServiceFetcher $serviceFetcher,
        private readonly CategoryFetcher $categoryFetcher,
        private readonly VisitFetcher $visitFetcher,
        private readonly MaterialFetcher $materialFetcher,
    )
    {
    }

    /**
     * @throws Exception
     */
    #[Route('/visits/clients', name: 'app_employee_records')]
    #[IsGranted('ROLE_EMPLOYEE')]
    public function employeeRecords(Request $request): Response
    {
        $filter = new Filter\Filter();

        $form = $this->createForm(Filter\Form::class, $filter);
        $form->handleRequest($request);

        /** @var User $employee */
        $employee = $this->getUser();

        $pagination = $this->visitFetcher->getActiveRecordsByEmployeeId(
            $employee->getId(),
            $filter,
            $request->query->getInt('page', 1),
            self::PER_PAGE,
            $request->query->get('sort', 'date_time'),
            $request->query->get('direction', 'desc')
        );

        return $this->render('app/main/visitEmployee.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/records', name: 'app_client_records')]
    #[IsGranted('ROLE_USER')]
    public function clientRecords(Request $request): Response
    {
        $filter = new Filter\Filter();

        $form = $this->createForm(Filter\Form::class, $filter);
        $form->handleRequest($request);

        /** @var User $client */
        $client = $this->getUser();

        $pagination = $this->visitFetcher->getActiveRecordsByClientId(
            $client->getId(),
            $filter,
            $request->query->getInt('page', 1),
            self::PER_PAGE,
            $request->query->get('sort', 'date_time'),
            $request->query->get('direction', 'desc')
        );

        return $this->render('app/main/visitClient.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
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

    /**
     * @throws Exception
     */
    #[Route('/appointment', name: 'app_appointment', methods: ['POST'])]
    public function actionPost(Request $request, Create\Handler $handler): Response
    {
        $serviceId = $request->request->get('serviceId');
        /** @var User $currentClient */
        $currentClient = $this->getUser();
        $command = new Create\Command($currentClient->getId());

        $form = $this->createForm(Create\Form::class, $command);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Вы успешно записаны');
                return $this->redirect($this->generateUrl('app_home', [
                    '_fragment' => 'booking',
                ]));
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->redirect($this->generateUrl('app_booking', [
            'serviceId' => $serviceId,
        ]));
    }

    /**
     * @throws \Exception
     */
    #[Route('/visit/{id}/edit', name: 'app_visit_move')]
    public function moveRecord(Visit $visit, Request $request, Move\Handler $handler): Response
    {
        /** @var User $employee */
        $employee = $this->getUser();

        $command = new Move\Command($visit->getId());

        $form = $this->createForm(Move\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Запись успешно перенесена');
                return $this->redirect($this->generateUrl('app_employee_records', [
                    '_fragment' => 'list',
                ]));
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/main/visit/edit.html.twig', [
            'visit' => $visit,
            'employeeId' => $employee->getId(),
        ]);
    }

    /**
     * @throws \Exception
     * @throws \PHPUnit\Exception
     */
    #[Route('/visit/{id}/close', name: 'app_visit_close')]
    public function closeRecord(Visit $visit, Request $request, Close\Handler $handler): Response
    {
        $command = new Close\Command($visit->getId());

        $form = $this->createForm(Close\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Запись успешно закрыта');
                return $this->redirectToRoute('app_employee_records', [
                    '_fragment' => 'list',
                ]);
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/main/visit/close.html.twig', [
            'visit' => $visit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/visit/{id}/cancel', name: 'app_visit_cancel')]
    public function cancelRecord(Visit $visit, Request $request, Cancel\Handler $handler): Response
    {
        $command = new Cancel\Command($visit->getId());

        try {
            $handler->handle($command);
            $this->addFlash('success', 'Запись успешно отменена');
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_client_records');
    }



    /**
     * @throws Exception
     */
    #[Route('/services/listByService', name: 'app_service_by_service')]
    public function getListByServiceId(Request $request): Response
    {
        $serviceId = $request->request->get('serviceId');

        if ($serviceId) {
            $services = $this->visitFetcher->getById($serviceId);
        } else {
            $services = $this->visitFetcher->findAll();
        }

        return $this->render('app/main/filters/_servicesByCategory.html.twig', [
            'services' => $services,
        ]);
    }
}
