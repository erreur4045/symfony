<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : LoginInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Interfaces\Security;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

/**
 * @Route("/login", name="app_login")
 */
interface LoginInterface
{
    /**
     * Login constructor.
     * @param Environment $environment
     */
    public function __construct(Environment $environment);

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(AuthenticationUtils $authenticationUtils): Response;
}
