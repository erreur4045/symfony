<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : MoreComInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Interfaces\Comments;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

/**
 * Class MoreCom
 * @package App\Actions\Comments
 * @Route("tricks/details/more_com", name="more.coms")
 *
 */
interface MoreComInterface
{
    /**
     * MoreCom constructor.
     * @param UrlGeneratorInterface $router
     * @param Filesystem $filesystem
     * @param Environment $environment
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        UrlGeneratorInterface $router,
        Filesystem $filesystem,
        Environment $environment,
        EntityManagerInterface $manager,
        TokenStorageInterface $tokenStorage
    );

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(Request $request);
}
