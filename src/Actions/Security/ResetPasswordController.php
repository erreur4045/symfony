<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : ResetPassword.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Security;

use App\Actions\OwnAbstractController;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends OwnAbstractController
{
    /**
     * @Route("/reset_password/{slug}", name="app_recoverypassword")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $encoder)
    {
        /** @var User $user */
        $user = $this->manager->getRepository(User::class)
            ->findOneBy(['token' => $request->attributes->get('slug')]);
        if (!empty($user)) {
        /** @var Form $form */
            $form = $this->formResolverRecoveryPassword->getForm($request, ResetPasswordType::class);
            if ($form->isSubmitted() && $form->isValid() && $user->getMail() == $form['email']->getData()) {
                $this->formResolverRecoveryPassword->treatment($form, $user);
                return new RedirectResponse($this->router->generate('home'));
            }
            return new Response($this->templating->render('security/resetpassword.html.twig', [
                        'form' => $form->createView()
                    ]));
        } else {
            return new RedirectResponse($this->router->generate('home'));
        }
    }
}
