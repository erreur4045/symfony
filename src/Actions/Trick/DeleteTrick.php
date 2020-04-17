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
use App\Responder\Interfaces\ResponderInterface;
use App\Traits\DoctrineTools;
use App\Traits\UploaderFileTools;
use App\Traits\ViewsTools;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/delete/{slug}", name="delete.trick")
 * @IsGranted("ROLE_USER")
 */
class DeleteTrick
{
    use UploaderFileTools, PictureTools, DoctrineTools, ViewsTools;

    private FlashBagInterface $bag;
    private Filesystem $filesystem;
    private string $tricksPicturesDirectory;
    private PictureslinkRepository $pictureRepo;
    private ResponderInterface $responder;

    /**
     * DeleteTrick constructor.
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
        return $this->responder->redirect('/');
    }
}
