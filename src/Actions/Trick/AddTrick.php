<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : AddTrickController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Trick;

use App\Actions\Interfaces\Trick\AddTrickInterface;
use App\Entity\User;
use App\Form\FigureType;
use App\Services\FormResolvers\FormResolverTricks;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @Route("/addtrick", name="addtrick")
 */
class AddTrick implements AddTrickInterface
{
    /** @var Environment  */
    private $templating;
    /** @var UrlGeneratorInterface  */
    private $router;
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var TokenStorageInterface  */
    private $tokenStorage;
    /** @var FormResolverTricks */
    private $formResolverTricks;

    /**
     * AddTrick constructor.
     * @param Environment $templating
     * @param UrlGeneratorInterface $router
     * @param EntityManagerInterface $manager
     * @param TokenStorageInterface $tokenStorage
     * @param FormResolverTricks $formResolverTricks
     */
    public function __construct(
        Environment $templating,
        UrlGeneratorInterface $router,
        EntityManagerInterface $manager,
        TokenStorageInterface $tokenStorage,
        FormResolverTricks $formResolverTricks
    ) {
        $this->templating = $templating;
        $this->router = $router;
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
        $this->formResolverTricks = $formResolverTricks;
    }


    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        /** @var Form $form */
        $form = $this->formResolverTricks->getForm($request, FigureType::class);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->formResolverTricks->addTrick($form, $user);
            return new RedirectResponse($this->router->generate('home'));
        }

        return new Response($this->templating->render('tricks/newtrick.html.twig', [
                    'form' => $form->createView(),
                    'h1' => 'Ajout d\'une figure'
                ]));
    }
}
