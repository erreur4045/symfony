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

use App\Actions\Medias\Picture\PictureTools;
use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Repository\PictureslinkRepository;
use App\Traits\DoctrineTools;
use App\Traits\UploaderFileTools;
use App\Traits\ViewsTools;
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
    use UploaderFileTools, PictureTools, DoctrineTools, ViewsTools;

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
    /** @var PictureslinkRepository */
    private $pictureRepo;

    /**
     * DeleteTrick constructor.
     * @param UrlGeneratorInterface $router
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param Filesystem $filesystem
     * @param string $tricksPicturesDirectory
     * @param PictureslinkRepository $pictureRepo
     */
    public function __construct(UrlGeneratorInterface $router, EntityManagerInterface $manager, FlashBagInterface $bag, Filesystem $filesystem, string $tricksPicturesDirectory, PictureslinkRepository $pictureRepo)
    {
        $this->router = $router;
        $this->manager = $manager;
        $this->bag = $bag;
        $this->filesystem = $filesystem;
        $this->tricksPicturesDirectory = $tricksPicturesDirectory;
        $this->pictureRepo = $pictureRepo;
    }


    /**
     * @param Figure $figure
     * @return RedirectResponse
     */
    public function __invoke(Figure $figure)
    {
        /** @var Pictureslink $image */
        $image = $this->pictureRepo->getByTrick($figure);
        /** @var Pictureslink $images */
        foreach ($image as $images) {
            $this->removeExpireFile($images);
        }
        $this->removeFromDataBase($figure);
        $this->displayMessage('success', 'La figure a été supprimée');
        return new RedirectResponse($this->router->generate('home'));
    }
}
