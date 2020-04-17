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

use App\Entity\Comments;
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
     */
    public function __construct(
        FlashBagInterface $bag,
        TokenStorageInterface $tokenStorage,
        FormResolverComment $formResolverComment,
        ResponderInterface $responder,
        PictureslinkRepository $pictureRepo,
        FigureRepository $figureRepo,
        VideolinkRepository $videoRepo
    ) {
        $this->bag = $bag;
        $this->tokenStorage = $tokenStorage;
        $this->formResolverComment = $formResolverComment;
        $this->responder = $responder;
        $this->pictureRepo = $pictureRepo;
        $this->figureRepo = $figureRepo;
        $this->videoRepo = $videoRepo;
    }


    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws EntityNotFoundException
     * @throws Exception
     */
    public function __invoke(Request $request)
    {
        $figure = $this->figureRepo->findOneBy(['slug' => $request->attributes->get('slug')]);
        if (is_null($figure)) {
            throw new EntityNotFoundException('Cette figure n\'existe pas');
        }
        [$image, $video, $hasOtherMedia, $user] = $this->getDataForViewFrom($figure);
        $form = $this->formResolverComment->getForm($request, CommentType::class);
        if ($form->isSubmitted() && $form->isValid() && $user != null) {
            $this->formResolverComment->addComment($form, $user, $figure);
            $this->displayMessage('success', 'Votre commentaire a été ajouté');
            $context = ['slug' => $figure->getSlug()];
            return $this->responder->redirect(self::TRICK, $context);
        }

        /** @var Comments $comments */
        $comments = $this->commentRepo->findBy(['figure' => $figure->getId()], [], Comments::LIMIT_PER_PAGE, null);
        $contextView = [
            'form' => $form->createView(),
            'data' => $figure,
            'image' => $image,
            'video' => $video,
            'comment' => $comments,
            'user' => $user,
            'emptyMedia' => $hasOtherMedia,
            'rest' => $this->getPageMaxCommentFrom($figure) > 1,
            'pagemax' => $this->getPageMaxCommentFrom($figure)
        ];
        return $this->responder->render(self::TRICKS_TWIG, $contextView);
    }
}
