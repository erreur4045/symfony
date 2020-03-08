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
    public function __invoke(Comments $comment, Request $request)
    {
        /** @var Figure $datatricks */
        $datatricks = $this->manager
            ->getRepository(Figure::class)
            ->findOneBy(['id' => $request->attributes->get('comment')->getIdfigure()->getId()]);
        if ($comment->getUser()->getMail() == $this->tokenStorage->getToken()->getUser()->getMail()) {
            $this->manager->remove($comment);
            $this->manager->flush();
            $this->bag->add('success', 'Votre commentaire a été supprimé');
            return new RedirectResponse($this->router->generate('trick', ['slug' => $datatricks->getSlug()]));
        } else {
            $this->bag->add('warning', 'Vous ne pouvez pas supprimer ce commentaire');
        }
        return new RedirectResponse($this->router->generate('trick', ['slug' => $datatricks->getSlug()]));
    }
}
