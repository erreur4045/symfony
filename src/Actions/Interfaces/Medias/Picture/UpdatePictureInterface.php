<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : UpdatePictureInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Interfaces\Medias\Picture;

use App\Services\FormResolvers\FormResolverMedias;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

/**
 * @Route("/media/update/picture/{id}", name="update.picture")
 * @IsGranted("ROLE_USER")
 */
interface UpdatePictureInterface
{
    /**
     * UpdatePicture constructor.
     * @param EntityManagerInterface $manager
     * @param TokenStorageInterface $tokenStorage
     * @param FormResolverMedias $formResolverMedias
     * @param FlashBagInterface $bag
     * @param UrlGeneratorInterface $router
     * @param Environment $environment
     */
    public function __construct(
        EntityManagerInterface $manager,
        TokenStorageInterface $tokenStorage,
        FormResolverMedias $formResolverMedias,
        FlashBagInterface $bag,
        UrlGeneratorInterface $router,
        Environment $environment
    );


    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function __invoke($id, Request $request);
}
