<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : UpdateVideoController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Medias\Video;

use App\Actions\Interfaces\Medias\Video\UpdateVideoInterface;
use App\Form\VideolinkType;
use App\Repository\FigureRepository;
use App\Repository\VideolinkRepository;
use App\Responder\Interfaces\ResponderInterface;
use App\Services\FormResolvers\FormResolverMedias;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/media/update/video/{id}", name="update.video")
 * @IsGranted("ROLE_USER")
 */
class UpdateVideo implements UpdateVideoInterface
{
    const TRICK = 'trick';
    const UPDATE_VIDEO_TWIG = 'media/UpdateVideo.html.twig';
    private FormResolverMedias $formResolverMedias;
    private FlashBagInterface $bag;
    private ResponderInterface $responder;
    private FigureRepository $figureRepo;
    private VideolinkRepository $videoRepo;

    /**
     * UpdateVideo constructor.
     * @param FormResolverMedias $formResolverMedias
     * @param FlashBagInterface $bag
     * @param ResponderInterface $responder
     * @param FigureRepository $figureRepo
     * @param VideolinkRepository $videoRepo
     */
    public function __construct(
        FormResolverMedias $formResolverMedias,
        FlashBagInterface $bag,
        ResponderInterface $responder,
        FigureRepository $figureRepo,
        VideolinkRepository $videoRepo
    ) {
        $this->formResolverMedias = $formResolverMedias;
        $this->bag = $bag;
        $this->responder = $responder;
        $this->figureRepo = $figureRepo;
        $this->videoRepo = $videoRepo;
    }


    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function __invoke(Request $request)
    {
        $idVideo = $request->attributes->getInt('id');
        $expiredVideo = $this->videoRepo->find($idVideo);
        $idFigureFrom = $expiredVideo->getFigure()->getId();
        $figure = $this->figureRepo->findOneBy(['id' => $idFigureFrom]);
        $form = $this->formResolverMedias->getForm($request, VideolinkType::class);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->formResolverMedias->updateVideoLink($form, $figure, $expiredVideo);
            $this->bag->add('success', 'La video a été modifiée');
            $context = ['slug' => $figure->getSlug()];
            return  $this->responder->redirect(self::TRICK, $context);
        }

        $contextView = [
            'form' => $form->createView(),
            'title' => 'Changer une image'
        ];
        return $this->responder->render(self::UPDATE_VIDEO_TWIG, $contextView);
    }
}
