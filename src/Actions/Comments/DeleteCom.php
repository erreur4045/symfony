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

use App\Actions\Interfaces\Comments\DeleteComInterface;
use App\Entity\Comments;
use App\Entity\Figure;
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
class DeleteCom implements DeleteComInterface
{
    /** @var UrlGeneratorInterface  */
    private $router;
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var FlashBagInterface  */
    private $bag;
    /** @var TokenStorageInterface  */
    private $tokenStorage;

    /**
     * DeleteCom constructor.
     * @param UrlGeneratorInterface $router
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        UrlGeneratorInterface $router,
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        TokenStorageInterface $tokenStorage
    ) {
        $this->router = $router;
        $this->manager = $manager;
        $this->bag = $bag;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param Comments $comment
     * @param Request $request
     * @return RedirectResponse
     */
    public function __invoke(
        Comments $comment,
        Request $request
    ) {
        $trick = $this->getTrickFromCommentAttribute($request);
        if (!$this->isConnectedUserConsistentWithCommentUser($comment))
            $this->bag->add('warning', 'Vous ne pouvez pas supprimer ce commentaire');
        else
            $this->deleteComment($comment);
        return new RedirectResponse($this->router->generate('trick', ['slug' => $trick->getSlug()]));
    }

    /**
     * @param Comments $comment
     * @return bool
     */
    public function isConnectedUserConsistentWithCommentUser(Comments $comment): bool
    {
        return $comment->getUser() == $this->tokenStorage->getToken()->getUser();
    }

    /**
     * @param Comments $comment
     */
    public function deleteComment(Comments $comment): void
    {
        $this->manager->remove($comment);
        $this->manager->flush();
        $this->bag->add('success', 'Votre commentaire a été supprimé');
    }

    /**
     * @param Request $request
     * @return Figure|object|null
     */
    public function getTrickFromCommentAttribute(Request $request)
    {
        $commentAttribute = $request->attributes->get('comment');
        $trickId = $commentAttribute->getIdfigure()->getId();
        /** @var Figure $trick */
        $figureRepo = $this->manager->getRepository(Figure::class);
        $trick = $figureRepo->findOneBy(['id' => $trickId]);
        return $trick;
    }
}
