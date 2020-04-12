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
 * Trait CommentsTools
 * @package App\Actions\Comments
 */
trait CommentsTools
{
    /** @var TokenStorageInterface  */
    private $tokenStorage;
    /** @var FigureRepository */
    private $tricksRepo;
    /** @var UrlGeneratorInterface  */
    private $router;
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var CommentsRepository */
    private $commentsRepo;

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
        $trickId = $this->getTrickFromIdComment($request)->getId();
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
     * @return Comments
     */
    public function getComment(Request $request)
    {
        $commentId = $this->getIdCommentFrom($request);
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
     * @param Request $request
     * @return Figure
     */
    public function getTrickFromIdComment(Request $request) :Figure
    {
        $comment = $this->commentsRepo->find($this->getIdCommentFrom($request));
        return $comment->getFigure();
    }

    /**
     * @param Comments $comment
     */
    public function deleteComment(Comments $comment): void
    {
        $this->manager->remove($comment);
        $this->manager->flush();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getIdCommentFrom(Request $request)
    {
        return $request->get('id');
    }
}
