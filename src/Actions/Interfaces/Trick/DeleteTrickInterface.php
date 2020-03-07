<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : DeleteTrickInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Interfaces\Trick;

use App\Entity\Figure;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/delete/{slug}", name="delete.trick")
 * @IsGranted("ROLE_USER")
 */
interface DeleteTrickInterface
{
    /**
     * DeleteTrick constructor.
     * @param UrlGeneratorInterface $router
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param Filesystem $filesystem
     * @param string $tricksPicturesDirectory
     */
    public function __construct(
        UrlGeneratorInterface $router,
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        Filesystem $filesystem,
        string $tricksPicturesDirectory
    );

    /**
     * @param Figure $figure
     * @return RedirectResponse
     */
    public function __invoke(Figure $figure);
}
