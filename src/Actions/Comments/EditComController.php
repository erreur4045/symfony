<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : EditComController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Comments;


use App\Actions\OwnAbstractController;
use App\Entity\Comments;
use App\Entity\Figure;
use App\Form\EditComType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class EditComController extends OwnAbstractController
{
    /**
     * @Route("/editcom/{id}", name="edit.comment")
     */
    public function editCom(UserInterface $user = null, Request $request)
    {
        if ($user == null) {
            return new Response(
                $this->environment->render(
                    'block_for_include/no_connect.html.twig',
                    [
                    ]
                )
            );
        }
        /** @var Comments $comment */
        $comment = $this->manager->getRepository(Comments::class)->findOneBy(['id' => $request->attributes->get('id')]);

        /** @var Figure $datatricks */
        $datatricks = $this->manager->getRepository(Figure::class)->findOneBy(['id' => $comment->getIdfigure()]);

        if ($comment->getUser()->getMail() == $this->tokenStorage->getToken()->getUser()->getMail()) {
            $form = $this->formResolverComment->getForm($request, EditComType::class);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->formResolverComment->updateCom($form, $comment);
                return new RedirectResponse($this->router
                    ->generate('trick', ['slug' => $datatricks->getSlug()]));
            }
            return new Response(
                $this->environment->render(
                    'comments/index.html.twig',
                    [
                        'form' => $form->createView(),
                        'comment' => $comment->getText()
                    ]
                )
            );
        } else {
            $this->bag->add('warning', 'Vous ne pouvez pas modifier ce commentaire');
        }
        return new RedirectResponse($this->router->generate('trick', ['slug' => $datatricks->getSlug()]));
    }
}