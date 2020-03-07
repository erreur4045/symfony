<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : GetHomePageInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Interfaces\Home;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @Route("/", name="home")
 */
interface GetHomePageInterface
{
    /**
     * GetHomePage constructor.
     * @param Environment $environment
     * @param EntityManagerInterface $manager
     */
    public function __construct(
        Environment $environment,
        EntityManagerInterface $manager
    );

    /**
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke();
}
