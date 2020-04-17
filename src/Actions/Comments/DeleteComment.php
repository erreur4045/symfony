<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : DeletecomController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Comments;

use App\Entity\Comments;
use App\Repository\CommentsRepository;
use App\Repository\FigureRepository;
use App\Responder\Interfaces\ResponderInterface;
use App\Traits\CommentsTools;
use App\Traits\RequestTools;
use App\Traits\ViewsTools;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/deletecom/{id}", name="delete.comment")
 * @IsGranted("ROLE_USER")
 */
class DeleteComment
{
    use CommentsTools, ViewsTools, RequestTools;

    const ROUTE_NAME = 'trick';

    private TokenStorageInterface $tokenStorage;
    private FigureRepository $tricksRepo;
    private CommentsRepository $commentsRepo;
    private FlashBagInterface $bag;
    private ResponderInterface $responder;

    /**
     * DeleteComment constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param FigureRepository $tricksRepo
     * @param CommentsRepository $commentsRepo
     * @param FlashBagInterface $bag
     * @param ResponderInterface $responder
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        FigureRepository $tricksRepo,
        CommentsRepository $commentsRepo,
        FlashBagInterface $bag,
        ResponderInterface $responder
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->tricksRepo = $tricksRepo;
        $this->commentsRepo = $commentsRepo;
        $this->bag = $bag;
        $this->responder = $responder;
    }


    /**
     * @param Request $request
     * @param Comments $comments
     * @return RedirectResponse
     */
    public function __invoke(
        Request $request,
        Comments $comments
    ):RedirectResponse {
        //todo: voir avec M le delegate sur les comments ajouter par l'appel Ajax
        $trick = $this->getTrick($request);
        if (!$this->isConnectedUserConsistentWithCommentUser($comments)) {
            $this->displayMessage('warning', 'Vous ne pouvez pas supprimer ce commentaire');
        } else {
            $this->commentsRepo->deleteComment($comments);
            $this->displayMessage('success', 'Votre commentaire a été supprimé');
        }
        $context = ['slug' => $trick->getSlug()];

        return $this->responder->redirect(self::ROUTE_NAME, $context);
    }
}
