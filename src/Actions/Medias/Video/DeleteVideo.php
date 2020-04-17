<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : DeleteVideo.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Medias\Video;

use App\Repository\VideolinkRepository;
use App\Responder\Interfaces\ResponderInterface;
use App\Traits\ViewsTools;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/media/delete/video/{id}", name="delete.video")
 * @IsGranted("ROLE_USER")
 */
class DeleteVideo
{
    use ViewsTools;

    const TRICK = 'trick';

    private FlashBagInterface $bag;
    private VideolinkRepository $videoRepo;
    private ResponderInterface $responder;

    /**
     * DeleteVideo constructor.
     * @param FlashBagInterface $bag
     * @param VideolinkRepository $videoRepo
     * @param ResponderInterface $responder
     */
    public function __construct(
        FlashBagInterface $bag,
        VideolinkRepository $videoRepo,
        ResponderInterface $responder
    ) {
        $this->bag = $bag;
        $this->videoRepo = $videoRepo;
        $this->responder = $responder;
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function __invoke(Request $request) :RedirectResponse
    {
        $idVideo = $request->attributes->getInt('id');
        $video = $this->videoRepo->findOneBy(['id' => $idVideo]);
        $this->videoRepo->removeVideo($video);

        $this->bag->add('success', 'La figure a été mise a jour');
        $this->displayMessage('success', 'La figure a été mise a jour');
        $context = ['slug' => $video[0]->getFigure()->getSlug()];
        return $this->responder->redirect(self::TRICK, $context);
    }
}
