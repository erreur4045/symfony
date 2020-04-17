<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : GetTrickController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Trick;

use App\Form\CommentType;
use App\Repository\CommentsRepository;
use App\Repository\FigureRepository;
use App\Repository\PictureslinkRepository;
use App\Repository\VideolinkRepository;
use App\Responder\Interfaces\ResponderInterface;
use App\Services\FormResolvers\FormResolverComment;
use App\Traits\TrickTools;
use App\Traits\ViewsTools;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/tricks/details/{slug}", name="trick")
 */
class GetTrick
{
    const TRICK = 'trick';
    const TRICKS_TWIG = 'tricks/trick.html.twig';

    use TrickTools, ViewsTools;

    private FlashBagInterface $bag;
    private TokenStorageInterface $tokenStorage;
    private FormResolverComment $formResolverComment;
    private ResponderInterface $responder;
    private PictureslinkRepository $pictureRepo;
    private FigureRepository $figureRepo;
    private VideolinkRepository $videoRepo;
    private CommentsRepository $commentRepo;

    /**
     * GetTrick constructor.
     * @param FlashBagInterface $bag
     * @param TokenStorageInterface $tokenStorage
     * @param FormResolverComment $formResolverComment
     * @param ResponderInterface $responder
     * @param PictureslinkRepository $pictureRepo
     * @param FigureRepository $figureRepo
     * @param VideolinkRepository $videoRepo
     * @param CommentsRepository $commentRepo
     */
    public function __construct(
        FlashBagInterface $bag,
        TokenStorageInterface $tokenStorage,
        FormResolverComment $formResolverComment,
        ResponderInterface $responder,
        PictureslinkRepository $pictureRepo,
        FigureRepository $figureRepo,
        VideolinkRepository $videoRepo,
        CommentsRepository $commentRepo
    ) {
        $this->bag = $bag;
        $this->tokenStorage = $tokenStorage;
        $this->formResolverComment = $formResolverComment;
        $this->responder = $responder;
        $this->pictureRepo = $pictureRepo;
        $this->figureRepo = $figureRepo;
        $this->videoRepo = $videoRepo;
        $this->commentRepo = $commentRepo;
    }


    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws EntityNotFoundException
     * @throws Exception
     */
    public function __invoke(Request $request)
    {
        $figure = $this->getFigureFrom($request);
        if (is_null($figure)) {
            throw new EntityNotFoundException('Cette figure n\'existe pas');
        }
        $user = $this->tokenStorage->getToken()->getUser();
        $form = $this->formResolverComment->getForm($request, CommentType::class);
        if ($form->isSubmitted() && $form->isValid() && $user != null) {
            $this->formResolverComment->addComment($form, $user, $figure);
            $this->displayMessage('success', 'Votre commentaire a été ajouté');
            $context = ['slug' => $figure->getSlug()];
            return $this->responder->redirect(self::TRICK, $context);
        }

        $contextView = [
            'form' => $form->createView(),
            'figure' => $figure,
            'image' => $this->pictureRepo->getByTrick($figure),
            'video' => $this->videoRepo->getByTrick($figure),
            'comment' => $this->commentRepo->getByTrick($figure),
            'user' => $user,
            'emptyMedia' => $this->isOtherMedia($figure),
            'pagemax' => $this->getPageMaxCommentFrom($figure),
            'rest' => $this->getPageMaxCommentFrom($figure) > 1,
        ];
        return $this->responder->render(self::TRICKS_TWIG, $contextView);
    }
}
