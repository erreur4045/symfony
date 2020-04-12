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
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class CommentsTools
 * @package App\Actions\Comments
 */
abstract class  CommentsTools
{

    /** @var EntityManagerInterface  */
    private $manager;
    /** @var TokenStorageInterface  */
    private $tokenStorage;
    /** @var FigureRepository */
    private $tricksRepo;
    /** @var CommentsRepository */
    protected $commentsRepo;
    /** @var UrlGeneratorInterface  */
    private $router;

    /**
     * CommentsTools constructor.
     * @param EntityManagerInterface $manager
     * @param TokenStorageInterface $tokenStorage
     * @param FigureRepository $tricksRepo
     * @param CommentsRepository $commentsRepo
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        EntityManagerInterface $manager,
        TokenStorageInterface $tokenStorage,
        FigureRepository $tricksRepo,
        CommentsRepository $commentsRepo,
        UrlGeneratorInterface $router
    ) {
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
        $this->tricksRepo = $tricksRepo;
        $this->commentsRepo = $commentsRepo;
        $this->router = $router;
    }

    /**
     * @param Comments $comment
     * @return bool
     */
    public function isConnectedUserConsistentWithCommentUser(Comments $comment): bool
    {
        return $comment->getUser() === $this->getConnectedUser();
    }

    /**
     * @param Request $request
     * @return Figure|object|null
     */
    public function getTrick(Request $request)
    {
        $commentId = $request->attributes->getInt('id');
        $trickId = $this->getFigureFromIdComment($commentId)->getId();
        return $this->tricksRepo->findOneBy(['id' => $trickId]);
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
        return $this->router->generate('trick', ['slug' => $tricks->getSlug()]);
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
     * @return int|void
     */
    public function countCommentsByIdTrick()
    {

        return count($this->tricksRepo->findAll());
    }

    /**
     * @param $trickId
     * @param $offset
     * @return Comments[]
     */
    public function getCommentsToShow($trickId, $offset): array
    {
        return $this->commentsRepo->findBy(
            ['figure' => $trickId],
            [],
            Comments::LIMIT_PER_PAGE,
            $offset
        );
    }

    /**
     * @return string|UserInterface
     */
    public function getConnectedUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }

    /**
     * @param int $commentId
     * @return Figure|null
     */
    public function getFigureFromIdComment(int $commentId)
    {
        return $this->commentsRepo->getFigure($commentId)->getFigure();
    }
}
