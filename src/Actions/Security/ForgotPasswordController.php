<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : ForgotPasswordController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Security;

use App\Actions\OwnAbstractController;
use App\Form\PasswordRecoveryMailType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForgotPasswordController extends OwnAbstractController
{
    /**
     * @Route("/forgot_password", name="app_passwordrecovery")
     */
    public function passwordRecovery(Request $request)
    {
        /** @var Form $form */
        $form = $this->formResolverPasswordRecovery->getForm($request, PasswordRecoveryMailType::class);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->formResolverPasswordRecovery->treatment($form);
            return new RedirectResponse($this->router->generate('home'));
        }

        return new Response($this->templating->render('security/mailforpasswordrecovery.html.twig', [
                    'form' => $form->createView()
                ]));
    }
}
