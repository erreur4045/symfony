<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : GetDashboardInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Interfaces\Dashboard;

use App\Services\FormResolvers\FormResolverMedias;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

/**
 * @Route("/dashboard", name="app_dashboard")
 * @IsGranted("ROLE_USER")
 */
interface GetDashboardInterface
{
    /**
     * GetDashboard constructor.
     * @param Environment $environment
     * @param FormResolverMedias $formResolverMedias
     * @param EntityManagerInterface $manager
     * @param TokenStorageInterface $tokenStorage
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        Environment $environment,
        FormResolverMedias $formResolverMedias,
        EntityManagerInterface $manager,
        TokenStorageInterface $tokenStorage,
        UrlGeneratorInterface $router
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
