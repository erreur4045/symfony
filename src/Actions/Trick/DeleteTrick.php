<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : DeleteTrickController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Trick;

use App\Entity\Figure;
use App\Entity\Pictureslink;
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
class DeleteTrick
{

    /** @var UrlGeneratorInterface  */
    private $router;
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var FlashBagInterface  */
    private $bag;
    /** @var Filesystem  */
    private $filesystem;
    /** @var string */
    private $tricksPicturesDirectory;

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
    ) {
        $this->router = $router;
        $this->manager = $manager;
        $this->bag = $bag;
        $this->filesystem = $filesystem;
        $this->tricksPicturesDirectory = $tricksPicturesDirectory;
    }


    /**
     * @param Figure $figure
     * @return RedirectResponse
     */
    public function __invoke(Figure $figure)
    {
            /** @var Pictureslink $image */
            $image = $this->manager->getRepository(Pictureslink::class)->findBy(['figure' => $figure->getId()]);
        foreach ($image as $images) {
            $this->filesystem->remove([
                    '',
                    '',
                    $this->tricksPicturesDirectory . $images->getLinkpictures()
                ]);
        }
            $this->manager->remove($figure);
        $this->manager->flush();
        $this->bag->add('success', 'La figure a été supprimé');
        return new RedirectResponse($this->router->generate('home'));
    }
}
