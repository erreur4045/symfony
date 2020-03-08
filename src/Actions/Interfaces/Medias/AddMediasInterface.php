<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : AddMediasInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Interfaces\Medias;

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
 * @Route("/edit/medias/{slug}", name="add.medias")
 * @IsGranted("ROLE_USER")
 */
interface AddMediasInterface
{
    /**
     * AddMedias constructor.
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param Environment $environment
     * @param UrlGeneratorInterface $router
     * @param FormResolverMedias $formResolverMedias
     */
    public function __construct(
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        Environment $environment,
        UrlGeneratorInterface $router,
        FormResolverMedias $formResolverMedias
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
