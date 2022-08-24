<?php

namespace Brangerieau\SymfonyCmsBundle\Controller;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'symfony_cms_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('symfony_cms_dashboard');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@SymfonyCms/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'reset_password' => class_exists('SymfonyCasts\Bundle\ResetPassword\DependencyInjection\SymfonyCastsResetPasswordExtension'),
        ]);
    }

    #[Route(path: '/logout', name: 'symfony_cms_logout')]
    public function logout(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
