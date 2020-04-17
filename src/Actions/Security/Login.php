<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : LoginController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Security;

use App\Actions\Interfaces\Security\LoginInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

/**
 * @Route("/login", name="app_login")
 */
class Login implements LoginInterface
{
    private Environment $environment;

    /**
     * Login constructor.
     * @param Environment $environment
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function __invoke(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return new Response($this->environment->render(
            'security/login.html.twig',
            ['last_username' => $lastUsername,
                'error' => $error]
        ));
    }
}
