<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : RegisterInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Interfaces\Security;

use App\Services\FormResolvers\FormResolverRegistration;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * @Route("/registration", name="registration")
 */
interface RegisterInterface
{
    /**
     * Register constructor.
     * @param FormResolverRegistration $fromResolverRegistration
     * @param UrlGeneratorInterface $router
     * @param Environment $templating
     */
    public function __construct(
        FormResolverRegistration $fromResolverRegistration,
        UrlGeneratorInterface $router,
        Environment $templating
    );

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(Request $request);
}
