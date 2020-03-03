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

use App\Actions\OwnAbstractController;
use App\Entity\User;
use App\Form\FigureType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddTrick extends OwnAbstractController
{
    /**
     * @Route("/addtrick", name="addtrick")
     */
    public function addTrick(Request $request)
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
