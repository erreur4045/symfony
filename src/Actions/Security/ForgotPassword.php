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

use App\Form\PasswordRecoveryMailType;
use App\Services\FormResolvers\FormResolverPasswordRecovery;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * @Route("/forgot_password", name="app_passwordrecovery")
 */
class ForgotPassword
{
    /** @var Environment  */
    private $templating;
    /** @var  FormResolverPasswordRecovery */
    private $formResolverPasswordRecovery;
    /** @var UrlGeneratorInterface  */
    private $router;

    /**
     * ForgotPassword constructor.
     * @param Environment $templating
     * @param FormResolverPasswordRecovery $formResolverPasswordRecovery
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        Environment $templating,
        FormResolverPasswordRecovery $formResolverPasswordRecovery,
        UrlGeneratorInterface $router
    ) {
        $this->templating = $templating;
        $this->formResolverPasswordRecovery = $formResolverPasswordRecovery;
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(Request $request)
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
