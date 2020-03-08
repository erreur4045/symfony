<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : UpdateVideoInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Interfaces\Medias\Video;

use App\Services\FormResolvers\FormResolverMedias;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * @Route("/media/update/video/{id}", name="update.video")
 * @IsGranted("ROLE_USER")
 */
interface UpdateVideoInterface
{
    /**
     * UpdateVideo constructor.
     * @param FormResolverMedias $formResolverMedias
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param Environment $environment
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        FormResolverMedias $formResolverMedias,
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        Environment $environment,
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
