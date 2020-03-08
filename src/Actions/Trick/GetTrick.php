<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : GetTrickController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Trick;

use App\Actions\Interfaces\Trick\GetTrickInterface;
use App\Entity\Comments;
use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Entity\User;
use App\Entity\Videolink;
use App\Form\CommentType;
use App\Services\FormResolvers\FormResolverComment;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

/**
 * @Route("/tricks/details/{slug}", name="trick")
 */
class GetTrick implements GetTrickInterface
{
    /** @var Environment  */
    private $templating;
    /** @var UrlGeneratorInterface  */
    private $router;
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var FlashBagInterface  */
    private $bag;
    /** @var TokenStorageInterface  */
    private $tokenStorage;
    /** @var FormResolverComment  */
    private $formResolverComment;

    /**
     * GetTrick constructor.
     * @param Environment $templating
     * @param UrlGeneratorInterface $router
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param TokenStorageInterface $tokenStorage
     * @param FormResolverComment $formResolverComment
     */
    public function __construct(
        Environment $templating,
        UrlGeneratorInterface $router,
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        TokenStorageInterface $tokenStorage,
        FormResolverComment $formResolverComment
    ) {
        $this->templating = $templating;
        $this->router = $router;
        $this->manager = $manager;
        $this->bag = $bag;
        $this->tokenStorage = $tokenStorage;
        $this->formResolverComment = $formResolverComment;
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws EntityNotFoundException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(Request $request)
    {
        /** @var Figure $figure */
        $figure = $this->manager->getRepository(Figure::class)
            ->findOneBy(['slug' => $request->attributes->get('slug')]);
        if (is_null($figure)) {
            throw new EntityNotFoundException('Cette figure n\'existe pas');
        }
        /** @var Pictureslink $image */
        $image = $this->manager->getRepository(Pictureslink::class)->findBy(['figure' => $figure->getId()]);
        /** @var Videolink $video */
        $video = $this->manager->getRepository(Videolink::class)->findBy(['figure' => $figure->getId()]);
        $hasOthermedia = empty($this->manager->getRepository(Pictureslink::class)
            ->findBy(['figure' => $figure->getId(), 'first_image' => 0])) && empty($video) ? true : false;
        /** @var Form $form */
        $form = $this->formResolverComment->getForm($request, CommentType::class);
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        if ($form->isSubmitted() && $form->isValid() && $user != null) {
            $this->formResolverComment->addCom($form, $user, $figure);
            $this->bag->add('success', 'Votre commentaire a été ajouter');
            return new RedirectResponse($this->router->generate('trick', ['slug' => $figure->getSlug()]));
        }

        /** @var Comments $comments */
        $comments = $this->manager->getRepository(Comments::class)
            ->findBy(['idfigure' => $figure->getId()], [], Comments::LIMIT_PER_PAGE, null);
        $nbPageMaxCom = ceil(count($this->manager
                    ->getRepository(Comments::class)
                    ->findBy(['idfigure' => $figure->getId()])) / Comments::LIMIT_PER_PAGE);
        $rest = $nbPageMaxCom > 1 ? true : false;
        return new Response($this->templating->render('tricks/trick.html.twig', [
                    'form' => $form->createView(),
                    'data' => $figure,
                    'image' => $image,
                    'video' => $video,
                    'comment' => $comments,
                    'user' => $user,
                    'emptyMedia' => $hasOthermedia,
                    'rest' => $rest,
                    'pagemax' => $nbPageMaxCom
                ]));
    }
}
