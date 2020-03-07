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

use App\Actions\Interfaces\Security\ResetPasswordInterface;
use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Services\FormResolvers\FormResolverRecoveryPassword;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Twig\Environment;

/**
 * @Route("/reset_password/{slug}", name="app_recoverypassword")
 */
class ResetPassword implements ResetPasswordInterface
{
    /** @var Environment  */
    private $templating;
    /** @var UrlGeneratorInterface  */
    private $router;
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var FormResolverRecoveryPassword */
    private $formResolverRecoveryPassword;

    /**
     * ResetPassword constructor.
     * @param Environment $templating
     * @param UrlGeneratorInterface $router
     * @param EntityManagerInterface $manager
     * @param FormResolverRecoveryPassword $formResolverRecoveryPassword
     */
    public function __construct(
        Environment $templating,
        UrlGeneratorInterface $router,
        EntityManagerInterface $manager,
        FormResolverRecoveryPassword $formResolverRecoveryPassword
    ) {
        $this->templating = $templating;
        $this->router = $router;
        $this->manager = $manager;
        $this->formResolverRecoveryPassword = $formResolverRecoveryPassword;
    }

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return RedirectResponse|Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(Request $request, UserPasswordEncoderInterface $encoder)
    {
        /** @var User $user */
        $user = $this->manager->getRepository(User::class)
            ->findOneBy(['token' => $request->query->get('slug')]);
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
