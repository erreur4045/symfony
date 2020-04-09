<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : EditCom.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Comments;

use App\Entity\Comments;
use App\Entity\Figure;
use App\Repository\CommentsRepository;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

trait CommentsToolsTrait
{
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var TokenStorageInterface  */
    private $tokenStorage;
    /** @var CommentsRepository */
    private $commentsRepo;
    /** @var FigureRepository */
    private $tricksRepo;
    /** @var UrlGeneratorInterface  */
    private $router;

    /**
     * CommentsTools constructor.
     * @param EntityManagerInterface $manager
     * @param TokenStorageInterface $tokenStorage
     * @param CommentsRepository $commentsRepo
     * @param FigureRepository $tricksRepo
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        EntityManagerInterface $manager,
        TokenStorageInterface $tokenStorage,
        CommentsRepository $commentsRepo,
        FigureRepository $tricksRepo,
        UrlGeneratorInterface $router
    ) {
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
        $this->commentsRepo = $commentsRepo;
        $this->tricksRepo = $tricksRepo;
        $this->router = $router;
    }


    /**
     * @param Comments $comment
     * @return bool
     */
    public function isConnectedUserConsistentWithCommentUser(Comments $comment): bool
    {
        return $comment->getUser() == $this->getUserFromToken();
    }

    /**
     * @param Request $request
     * @return Figure|object|null
     */
    public function getTrick(Request $request)
    {
        $commentAttribute = $request->attributes->get('comment');
        $trickId = $commentAttribute->getIdfigure()->getId();
        /** @var Figure $trick */
        $figureRepo = $this->manager->getRepository(Figure::class);
        $trick = $figureRepo->findOneBy(['id' => $trickId]);
        return $trick;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getPageId(Request $request)
    {
        return $request->query->get('page');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getTrickId(Request $request)
    {
        return $request->query->get('figureid');
    }

    /**
     * @param Figure $tricks
     * @return string
     */
    public function getTrickUrl(Figure $tricks): string
    {
        return $this->router
            ->generate('trick', ['slug' => $tricks->getSlug()]);
    }

    /**
     * @param Request $request
     * @return Comments|null
     */
    public function getComment(Request $request)
    {
        $commentId = $request->attributes->get('id');
        return $this->commentsRepo->findOneBy(['id' => $commentId]);
    }


    /**
     * @param $figureId
     * @return int|void
     */
    public function countCommentsByIdTrick($figureId)
    {
        return count($this->commentsRepo->findBy(['idfigure' => $figureId]));
    }

    /**
     * @param $trickId
     * @param $offset
     * @return Comments[]
     */
    public function getCommentsToShow($trickId, $offset): array
    {
        return $this->commentsRepo->findBy(
            ['idfigure' => $trickId],
            [],
            Comments::LIMIT_PER_PAGE,
            $offset
        );
    }
}
