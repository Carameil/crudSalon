<?php

namespace App\Controller;

use App\Entity\User\User;
use App\ReadModel\CategoryFetcher;
use App\ReadModel\ServiceFetcher;
use App\UseCase\Visit\Create;
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

    /**
     * @throws Exception
     */
    #[Route('/appointment', name: 'app_appointment', methods: ['POST'])]
    public function actionPost(Request $request, Create\Handler $handler): Response
    {
        $data = $request->request->all();
        /** @var User $currentClient */
        $currentClient = $this->getUser();
        $command = new Create\Command($currentClient->getId());

        $form = $this->createForm(Create\Form::class, $command, [
            'csrf_protection' => false,
        ]);

        $form->submit($data);

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
            'serviceId' => $data['serviceId'],
        ]));
    }
}
