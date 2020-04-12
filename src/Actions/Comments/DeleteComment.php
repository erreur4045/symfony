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
use App\Traits\ViewsTools;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/deletecom/{id}", name="delete.comment")
 * @IsGranted("ROLE_USER")
 */
class DeleteComment
{
    use CommentsTools, ViewsTools;

    const ROUTE_NAME = 'trick';

    /** @var UrlGeneratorInterface  */
    private $router;
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var TokenStorageInterface  */
    private $tokenStorage;
    /** @var FigureRepository */
    private $tricksRepo;
    /** @var CommentsRepository */
    private $commentsRepo;
    /** @var FlashBagInterface */
    private $bag;
    /** @var Comments */
    private $comment;

    /**
     * DeleteComment constructor.
     * @param UrlGeneratorInterface $router
     * @param EntityManagerInterface $manager
     * @param TokenStorageInterface $tokenStorage
     * @param FigureRepository $tricksRepo
     * @param CommentsRepository $commentsRepo
     * @param FlashBagInterface $bag
     */
    public function __construct(
        UrlGeneratorInterface $router,
        EntityManagerInterface $manager,
        TokenStorageInterface $tokenStorage,
        FigureRepository $tricksRepo,
        CommentsRepository $commentsRepo,
        FlashBagInterface $bag
    ) {
        $this->router = $router;
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
        $this->tricksRepo = $tricksRepo;
        $this->commentsRepo = $commentsRepo;
        $this->bag = $bag;
    }


    /**
     * @param Request $request
     * @param Comments $comments
     * @return RedirectResponse
     */
    public function __invoke(
        Request $request,
        Comments $comments
    ) {
        $trick = $this->getTrick($request);
        if (!$this->isConnectedUserConsistentWithCommentUser($comments)) {
            $this->displayMessage('warning', 'Vous ne pouvez pas supprimer ce commentaire');
        } else {
            $this->deleteComment($comments);
            $this->displayMessage('success', 'Votre commentaire a été supprimé');
        }
        $context = ['slug' => $trick->getSlug()];
        return new RedirectResponse($this->router->generate(self::ROUTE_NAME, $context));
    }
}
