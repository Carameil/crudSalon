<?php

namespace App\Security;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class LoginFormAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly UserRepository  $userRepository,
        private readonly RouterInterface $router,
    )
    {
    }

    public function supports(Request $request): ?bool
    {
        return 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $csrfToken = $request->request->get('_csrf_token');

        return new Passport(
            new UserBadge($email, function ($userIdentifier) {
                return $this->userRepository->findByEmail($userIdentifier);
            }),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge(
                    'authenticate',
                    $csrfToken
                ),
                new RememberMeBadge()
            ]
        );

    }

    /**
     * @throws EntityNotFoundException
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $email = $request->request->get('email');
        $user = $this->userRepository->getByEmail($email);

        if ($user && $user->isAdmin()) {
            return new RedirectResponse(
                $this->router->generate('admin')
            );
        }
        return new RedirectResponse(
            $this->router->generate('app_home')
        );
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        $request->getSession()->getFlashBag()->add('error', 'Неверные аутентификационные данные');
        return new RedirectResponse(
            $this->router->generate('app_login')
        );
    }
}
