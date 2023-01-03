<?php

namespace App\Controller\Auth;

use App\UseCase\Client\SignUp;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignUpController extends AbstractController
{
    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/signup', name: 'app_signup')]
    public function request(Request $request, SignUp\Handler $handler): Response
    {
        $command = new SignUp\Command();

        $form = $this->createForm(SignUp\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'Вы успешно зарегестрировались');
                return $this->redirectToRoute('app_home');
            } catch (\DomainException $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('app/auth/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
