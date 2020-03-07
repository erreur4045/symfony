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

namespace App\Actions\Comments;

use App\Entity\Comments;
use App\Entity\Figure;
use App\Form\EditComType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @Route("/editcom/{id}", name="edit.comment")
 * @IsGranted("ROLE_USER")
*/
class EditCom
{

    /** @var Environment  */
    private $environment;
    /** @var UrlGeneratorInterface  */
    private $router;
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var FlashBagInterface  */
    private $bag;
    /** @var TokenStorageInterface  */
    private $tokenStorage;

    /**
     * EditCom constructor.
     * @param Environment $environment
     * @param UrlGeneratorInterface $router
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        Environment $environment,
        UrlGeneratorInterface $router,
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        TokenStorageInterface $tokenStorage
    ) {
        $this->environment = $environment;
        $this->router = $router;
        $this->manager = $manager;
        $this->bag = $bag;
        $this->tokenStorage = $tokenStorage;
    }

/**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(Request $request)
    {
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
            return new Response($this->environment->render('comments/index.html.twig', [
                        'form' => $form->createView(),
                        'comment' => $comment->getText()
                    ]));
        } else {
            $this->bag->add('warning', 'Vous ne pouvez pas modifier ce commentaire');
        }
        return new RedirectResponse($this->router->generate('trick', ['slug' => $datatricks->getSlug()]));
    }
}
