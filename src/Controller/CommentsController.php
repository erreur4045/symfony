<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Figure;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommentsController
{
    public function __construct(FlashBagInterface $bag, UrlGeneratorInterface $router, TokenStorageInterface $tokenStorage)
    {
        $this->bag = $bag;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
    }
    /**
     * @Route("/comments", name="comments")
     */
    public function index()
    {
        return $this->render('comments/index.html.twig', [
            'controller_name' => 'CommentsController',
        ]);
    }
    /**
     * @Route("/deletecom/{id}", name="delete.comment")
     * @param Comments $comment
     * @param ObjectManager $manager
     * @return RedirectResponse
     */
    public function deleteCom(Comments $comment, ObjectManager $manager)
    {
        if ($comment->getUser()->getName() == $this->tokenStorage->getToken()->getUser() OR $this->tokenStorage->getToken()->getUser()) {
            $manager->remove($comment);
            $manager->flush();
            $this->bag->add('success', 'Votre commentaire a été supprimé');
            return new RedirectResponse($this->router->generate('tricks'));
        }
        else $this->bag->add('warning', 'Vous ne pouvez pas supprimer ce commentaire');
        return new RedirectResponse($this->router->generate('tricks'));
    }

    //todo : creer page avec form contenant le commentaire puis MAJ
    /**
     * @Route("/editcom/{id}", name="edit.comment")
     * @param Comments $comment
     * @param ObjectManager $manager
     * @return RedirectResponse
     */
    public function editCom(Comments $comment, ObjectManager $manager)
    {
        if ($comment->getUser()->getName() == $this->tokenStorage->getToken()->getUser() OR $this->tokenStorage->getToken()->getUser()) {
            $manager->edit($comment);
            $manager->flush();
            $this->bag->add('success', 'Votre commentaire a été modifié');
            return new RedirectResponse($this->router->generate('tricks'));
        }
        else $this->bag->add('warning', 'Vous ne pouvez pas modifier ce commentaire');
        return new RedirectResponse($this->router->generate('tricks'));
    }
}
