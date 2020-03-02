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

use App\Actions\OwnAbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class LoginController extends OwnAbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param           AuthenticationUtils $authenticationUtils
     * @return          Response
     * @throws          LoaderError
     * @throws          RuntimeError
     * @throws          SyntaxError
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return new Response($this->templating->render(
            'security/login.html.twig',
            ['last_username' => $lastUsername,
                'error' => $error]
        ));
    }
}
