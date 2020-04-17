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

use App\Entity\User;
use App\Form\FigureType;
use App\Responder\Interfaces\ResponderInterface;
use App\Services\FormResolvers\FormResolverTricks;
use Exception;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/addtrick", name="addtrick")
 */
class AddTrick
{
    const TRICKS_NEWTRICK_TWIG = 'tricks/newtrick.html.twig';
    private TokenStorageInterface $tokenStorage;
    private FormResolverTricks $formResolverTricks;
    private ResponderInterface $responder;

    /**
     * AddTrick constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param FormResolverTricks $formResolverTricks
     * @param ResponderInterface $responder
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        FormResolverTricks $formResolverTricks,
        ResponderInterface $responder
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->formResolverTricks = $formResolverTricks;
        $this->responder = $responder;
    }


    /**
     * @param Request $request
     * @return RedirectResponse|Response
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
            return $this->responder->redirect('/');
        }

        $contextView = [
            'form' => $form->createView(),
            'h1' => 'Ajout d\'une figure'
        ];
        return $this->responder->render(self::TRICKS_NEWTRICK_TWIG, $contextView);
    }
}
