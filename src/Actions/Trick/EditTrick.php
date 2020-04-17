<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : EditTrickController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Trick;

use App\Form\FigureEditType;
use App\Repository\FigureRepository;
use App\Repository\PictureslinkRepository;
use App\Repository\VideolinkRepository;
use App\Responder\Interfaces\ResponderInterface;
use App\Services\FormResolvers\FormResolverTricks;
use App\Traits\TrickTools;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/edit/{slug}", name="edit.trick")
 * @IsGranted("ROLE_USER")
 */
class EditTrick
{
    const TRICKS_EDITTRICK_TWIG = 'tricks/edittrick.html.twig';
    const TRICK = 'trick';

    use TrickTools;

    private FormResolverTricks $formResolverTricks;
    private ResponderInterface $responder;
    private PictureslinkRepository $pictureRepo;
    private FigureRepository $figureRepo;
    private VideolinkRepository $videoRepo;

    /**
     * EditTrick constructor.
     * @param FormResolverTricks $formResolverTricks
     * @param ResponderInterface $responder
     * @param PictureslinkRepository $pictureRepo
     * @param FigureRepository $figureRepo
     * @param VideolinkRepository $videoRepo
     */
    public function __construct(
        FormResolverTricks $formResolverTricks,
        ResponderInterface $responder,
        PictureslinkRepository $pictureRepo,
        FigureRepository $figureRepo,
        VideolinkRepository $videoRepo
    ) {
        $this->formResolverTricks = $formResolverTricks;
        $this->responder = $responder;
        $this->pictureRepo = $pictureRepo;
        $this->figureRepo = $figureRepo;
        $this->videoRepo = $videoRepo;
    }


    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function __invoke(Request $request)
    {
        $figure = $this->getFigureFrom($request);
        if (is_null($figure)) {
            throw new NotFoundHttpException('La figure n\'existe pas');
        }
        /** @var Form $form */
        $form = $this->formResolverTricks->getForm($request, FigureEditType::class, $figure);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->formResolverTricks->updateTrick($figure);
            $context = ['slug' => $figure->getSlug()];
            return $this->responder->redirect(self::TRICK, $context);
        }
        $context = [
            'figure' => $figure,
            'form' => $form->createView(),
            'h1' => 'Modification de la figure',
            'emptyMedia' => $this->isOtherMedia($figure),
            'otherPicture' => $this->isOneOtherFirstImage($figure)
        ];
        return $this->responder->render(self::TRICKS_EDITTRICK_TWIG, $context);
    }
}
