<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Figure;
use App\Entity\User;
use App\Form\EditComType;
use App\Services\FormResolverComment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;

class CommentsController extends OwnAbstractController
{
    /**
     *
     *
     * @var FormResolverComment
     */
    private $formResolverComment;

    /**
     *
     *
     * @var FlashBagInterface
     */
    private $bag;

    /**
     *
     *
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     *
     *
     * @var UrlGeneratorInterface
     */
    private $router;

    public function __construct(
        Environment $templating,
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        UrlGeneratorInterface $router,
        TokenStorageInterface $tokenStorage,
        FormResolverComment $formResolverComment
    ) {
        $this->formResolverComment = $formResolverComment;
        $this->bag = $bag;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
        $this->manager = $manager;
        $this->templating = $templating;
    }

    /**
     * @Route("/deletecom/{id}", name="delete.comment")
     */
    public function deleteCom(UserInterface $user = null, Comments $comment, Request $request)
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

    /**
     * @Route("tricks/details/more_com", name="more.coms")
     */
    public function loadTricks(Request $request)
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $pageId = $request->query->get('page');
        $figureId = $request->query->get('figureid');
        $offset = $pageId * Comments::LIMIT_PER_PAGE - Comments::LIMIT_PER_PAGE;
        $nb_coms = $this->manager->getRepository(Comments::class)->findBy(['idfigure' => $figureId]);
        if ($nb_coms > Comments::LIMIT_PER_PAGE) {
            $rest = false;
        } else {
            $rest = $pageId * Comments::LIMIT_PER_PAGE < $nb_coms;
        }

        /** @var Comments $comsToShow */
        $comsToShow = $this->manager->getRepository(Comments::class)
            ->findBy(['idfigure' => $figureId], [], Comments::LIMIT_PER_PAGE, $offset);

        return new Response(
            $this->environment->render(
                'block_for_include/block_for_coms_ajax.html.twig',
                [
                'user' => $user,
                'comsToShow' => $comsToShow,
                'rest' => $rest
                ]
            )
        );
    }
}
