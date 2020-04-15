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

namespace App\Traits;

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
 * @package App\Traits
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
     * @param $pageId
     * @return float|int
     */
    protected function computeOffsetFrom($pageId)
    {
        return $pageId * Comments::LIMIT_PER_PAGE - Comments::LIMIT_PER_PAGE;
    }

    /**
     * @param int $countComments
     * @param $pageId
     * @return bool
     */
    protected function isRest(int $countComments, $pageId): bool
    {
        return $countComments > Comments::LIMIT_PER_PAGE ? false : $pageId * Comments::LIMIT_PER_PAGE < $countComments;
    }
}
