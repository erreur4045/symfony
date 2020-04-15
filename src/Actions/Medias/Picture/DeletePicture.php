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
use App\Repository\PictureslinkRepository;
use App\Traits\ViewsTools;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/media/delete/picture/{picture}", name="delete.image")
 * @IsGranted("ROLE_USER")
 */
class DeletePicture
{
    use PictureTools, ViewsTools;

    const TRICK_TWIG = 'trick';

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
    /** @var PictureslinkRepository */
    private $pictureRepo;

    /**
     * DeletePicture constructor.
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param Filesystem $filesystem
     * @param string $tricksPicturesDirectory
     * @param UrlGeneratorInterface $router
     * @param PictureslinkRepository $pictureRepo
     */
    public function __construct(
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        Filesystem $filesystem,
        string $tricksPicturesDirectory,
        UrlGeneratorInterface $router,
        PictureslinkRepository $pictureRepo
    ) {
        $this->manager = $manager;
        $this->bag = $bag;
        $this->filesystem = $filesystem;
        $this->tricksPicturesDirectory = $tricksPicturesDirectory;
        $this->router = $router;
        $this->pictureRepo = $pictureRepo;
    }

    public function __invoke(Request $request)
    {
        $imageName = $request->get('picture');
        /** @var Pictureslink $image */
        $image = $this->pictureRepo->findOneBy(['linkpictures' => $imageName]);

        if ($image->getFirstImage() != true) {
            $this->pictureRepo->deletePicture($image);
        } else {
            $this->setNewHeadlineImage($image);
            $this->pictureRepo->deletePicture($image);
        }
        $this->removeImageTrickOnServer($image);
        $this->displayMessage('success', 'La figure a été mise a jour');
        $context = ['slug' => $image->getFigure()->getSlug()];
        return new RedirectResponse($this->router->generate(self::TRICK_TWIG, $context));
    }
}
