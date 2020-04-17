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
use App\Responder\Interfaces\ResponderInterface;
use App\Traits\ViewsTools;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/media/delete/picture/{picture}", name="delete.image")
 * @IsGranted("ROLE_USER")
 */
class DeletePicture
{
    use PictureTools, ViewsTools;

    const TRICK_TWIG = 'trick';

    private FlashBagInterface $bag;
    private Filesystem $filesystem;
    private string $tricksPicturesDirectory;
    private PictureslinkRepository $pictureRepo;
    private ResponderInterface $responder;

    /**
     * DeletePicture constructor.
     * @param FlashBagInterface $bag
     * @param Filesystem $filesystem
     * @param string $tricksPicturesDirectory
     * @param PictureslinkRepository $pictureRepo
     * @param ResponderInterface $responder
     */
    public function __construct(
        FlashBagInterface $bag,
        Filesystem $filesystem,
        string $tricksPicturesDirectory,
        PictureslinkRepository $pictureRepo,
        ResponderInterface $responder
    ) {
        $this->bag = $bag;
        $this->filesystem = $filesystem;
        $this->tricksPicturesDirectory = $tricksPicturesDirectory;
        $this->pictureRepo = $pictureRepo;
        $this->responder = $responder;
    }

    public function __invoke(Request $request) :RedirectResponse
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
        return $this->responder->redirect(self::TRICK_TWIG, $context);
    }
}
