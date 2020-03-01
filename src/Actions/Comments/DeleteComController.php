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

use App\Actions\OwnAbstractController;
use App\Entity\Comments;
use App\Entity\Figure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DeleteComController extends OwnAbstractController
{
    /**
     * @Route("/deletecom/{id}", name="delete.comment")
     * @IsGranted("ROLE_USER")
     */
    public function deleteCom(Comments $comment, Request $request)
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
