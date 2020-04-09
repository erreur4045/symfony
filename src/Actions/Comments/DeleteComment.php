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
use App\Traits\ViewsToolsTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/deletecom/{id}", name="delete.comment")
 * @IsGranted("ROLE_USER")
 */
class DeleteComment
{
    use CommentsToolsTrait, ViewsToolsTrait;

    /** @var UrlGeneratorInterface  */
    private $router;
    /** @var EntityManagerInterface  */

    /**
     * DeleteCom constructor.
     * @param UrlGeneratorInterface $router
     */
    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Comments $comment
     * @param Request $request
     * @return RedirectResponse
     * @throws ORMException
     */
    public function __invoke(
        Comments $comment,
        Request $request
    ) {
        $trick = $this->getTrick($request);
        if (!$this->isConnectedUserConsistentWithCommentUser($comment)) {
            $this->displayMessage('warning', 'Vous ne pouvez pas supprimer ce commentaire');
        } else {
            $this->commentsRepo->deleteComment($comment);
            $this->displayMessage('success', 'Votre commentaire a été supprimé');
        }
        return new RedirectResponse($this->router->generate('trick', ['slug' => $trick->getSlug()]));
    }
}
