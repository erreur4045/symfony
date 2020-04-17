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
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Trait CommentsTools
 * @package App\Traits
 */
trait CommentsTools
{
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
