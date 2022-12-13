<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class SecurityController
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly Environment $renderer,
    ) {

    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return new Response($this->renderer->render('security/login.html.twig', ['error' => $authenticationUtils->getLastAuthenticationError()])) ;
    }

    public function logout(Security $security): Response
    {
        $security->logout(false);
        return new RedirectResponse($this->router->generate('app_login'));
    }
}
