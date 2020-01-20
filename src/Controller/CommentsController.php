<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Figure;
use App\Form\CommentType;
use App\Form\EditComType;
use App\Services\FormResolverComment;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;

class CommentsController
{
    /** @var EntityManagerInterface */
    private $manager;
    /**
     * @var FormResolverComment
     */
    private $formResolverComment;

    public function __construct(
        Environment $templating,
        FormFactoryInterface $formFactory,
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
        $this->formFactory = $formFactory;
        $this->templating = $templating;
    }

    /**
     * @Route("/deletecom/{id}", name="delete.comment")
     */
    public function deleteCom(UserInterface $user = null, Comments $comment, ObjectManager $manager, Request $request)
    {
        if($user == null){
            return new Response($this->templating->render('block_for_include/no_connect.html.twig', [
            ]));
        }

        /** @var Figure $datatricks */
        $datatricks = $this->manager
            ->getRepository(Figure::class)
            ->findOneBy(['id' => $request->attributes->get('comment')->getIdfigure()->getId()]);

        if ($comment->getUser()->getMail() == $this->tokenStorage->getToken()->getUser()->getMail()) {
            $manager->remove($comment);
            $manager->flush();
            $this->bag->add('success', 'Votre commentaire a été supprimé');
            return new RedirectResponse($this->router->generate('trick', ['slug' => $datatricks->getSlug()]));
        } else {
            $this->bag->add('warning', 'Vous ne pouvez pas supprimer ce commentaire');
        }
        return new RedirectResponse($this->router->generate('trick', ['slug' => $datatricks->getSlug()]));
    }

    /**
     * @Route("/editcom/{id}", name="edit.comment")
     * @param UserInterface|null $user
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function editCom(UserInterface $user = null, Request $request)
    {
        /** @var Comments $comment */
        $comment = $this->manager->getRepository(Comments::class)->findOneBy(['id' => $request->attributes->get('id')]);
        if($user == null){
            return new Response($this->templating->render('block_for_include/no_connect.html.twig', [
            ]));
        }
        /** @var Figure $datatricks */
        $datatricks = $this->manager->getRepository(Figure::class)->findOneBy(['id' => $comment->getIdfigure()]);
        $type = EditComType::class;

        if ($comment->getUser()->getMail() == $this->tokenStorage->getToken()->getUser()->getMail()) {
            $form = $this->formResolverComment->getForm($request, $type);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->formResolverComment->updateCom($form, $comment);
                return new RedirectResponse($this->router->generate('trick', ['slug' => $datatricks->getSlug()]));
            }
            return new Response($this->templating->render('comments/index.html.twig', [
                'form' => $form->createView(),
                'comment' => $comment->getText()
            ]));
        } else {
            $this->bag->add('warning', 'Vous ne pouvez pas modifier ce commentaire');
        }
        return new RedirectResponse($this->router->generate('trick', ['slug' => $datatricks->getSlug()]));
    }
}
