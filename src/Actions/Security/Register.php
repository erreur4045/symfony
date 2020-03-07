<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : LogoutController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Security;

use App\Form\RegistrationType;
use App\Services\FormResolvers\FormResolverRegistration;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * @Route("/registration", name="registration")
 */
class Register
{
    /** @var FormResolverRegistration */
    private $fromResolverRegistration;
    /** @var UrlGeneratorInterface  */
    private $router;
    /** @var Environment  */
    private $templating;

    /**
     * Register constructor.
     * @param FormResolverRegistration $fromResolverRegistration
     * @param UrlGeneratorInterface $router
     * @param Environment $templating
     */
    public function __construct(
        FormResolverRegistration $fromResolverRegistration,
        UrlGeneratorInterface $router,
        Environment $templating
    ) {
        $this->fromResolverRegistration = $fromResolverRegistration;
        $this->router = $router;
        $this->templating = $templating;
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
        $form = $this->fromResolverRegistration->getForm($request, RegistrationType::class);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->fromResolverRegistration->treatment($form);
            return new RedirectResponse($this->router->generate('app_login'));
        }

        return new Response($this->templating->render('security/registration.html.twig', [
                    'form' => $form->createView()
                ]));
    }
}
