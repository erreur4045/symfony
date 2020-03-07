<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : DeletePicture.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Medias\Picture;

use App\Entity\Pictureslink;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/media/delete/picture/{picture}", name="delete.image")
 * @IsGranted("ROLE_USER")
 */
class DeletePicture
{
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var FlashBagInterface  */
    private $bag;
    /** @var Filesystem  */
    private $filesystem;
    /** @var string */
    private $tricksPicturesDirectory;
    /** @var UrlGeneratorInterface  */
    private $router;

    /**
     * DeletePicture constructor.
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param Filesystem $filesystem
     * @param string $tricksPicturesDirectory
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        Filesystem $filesystem,
        string $tricksPicturesDirectory,
        UrlGeneratorInterface $router
    ) {
        $this->manager = $manager;
        $this->bag = $bag;
        $this->filesystem = $filesystem;
        $this->tricksPicturesDirectory = $tricksPicturesDirectory;
        $this->router = $router;
    }


    public function __invoke($picture)
    {
            /** @var Pictureslink $image */
            $image = $this->manager->getRepository(Pictureslink::class)->findBy(['linkpictures' => $picture]);
        if ($image[0]->getFirstImage() == true) {
            $this->manager->remove($image[0]);
            $this->manager->flush();
            $NewFirstImages = $this->manager->getRepository(Pictureslink::class)->findBy([
                    'figure' => $image[0]->getFigure()->getId(),
                    'first_image' => false
                ]);
            $NewFirstImages[0]->setFirstImage(1);
            $this->manager->persist($NewFirstImages[0]);
            $this->manager->flush();
        } else {
            $this->manager->remove($image[0]);
            $this->manager->flush();
        }
            $this->filesystem->remove([
                    '',
                    '',
                    $this->tricksPicturesDirectory . $image[0]->getLinkpictures()
                ]);
        $this->bag->add('success', 'La figure a été mise a jour');
        return new RedirectResponse($this->router->generate('trick', ['slug' => $image[0]->getFigure()->getSlug()]));
    }
}
