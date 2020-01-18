<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordRecoveryMailType;
use App\Form\RegistrationType;
use App\Form\ResetPasswordType;
use App\Services\FormResolverPasswordRecovery;
use App\Services\FormResolverRecoveryPassword;
use App\Services\FormResolverRegistration;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

class SecurityController
{
    /** @var Environment */
    private $environement;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var UrlGeneratorInterface */
    private $generator;

    /** @var FlashBagInterface */
    private $bag;

    /** @var EntityManagerInterface */
    private $manager;

    /** @var FormResolverRegistration */
    private $fromResolverRegistration;

    /** @var FormResolverPasswordRecovery */
    private $formResolverPasswordRecovery;

    /** @var  UrlGeneratorInterface */
    private $router;

    /** @var FormResolverRecoveryPassword */
    private $formResolverRecoveryPassword;

    public function __construct(
        Environment $environment,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $generator,
        FlashBagInterface $bag,
        EntityManagerInterface $manager,
        FormResolverRegistration $formResolverRegistration,
        FormResolverPasswordRecovery $formResolverPasswordRecovery,
        FormResolverRecoveryPassword $formResolverRecoveryPassword,
        UrlGeneratorInterface $router

    ) {
        $this->environement = $environment;
        $this->formFactory = $formFactory;
        $this->generator = $generator;
        $this->bag = $bag;
        $this->manager = $manager;
        $this->fromResolverRegistration = $formResolverRegistration;
        $this->formResolverPasswordRecovery = $formResolverPasswordRecovery;
        $this->formResolverRecoveryPassword = $formResolverRecoveryPassword;
        $this->router = $router;

    }

    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return new Response($this->environement->render('security/login.html.twig',
            ['last_username' => $lastUsername, 'error' => $error]));
    }

    /**
     * @Route("/inscription", name="registration")
     */
    public function registration(ObjectManager $manager, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $type = RegistrationType::class;
        $form = $this->fromResolverRegistration->getForm($request, $type);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->fromResolverRegistration->treatment($form);
            return new RedirectResponse($this->generator->generate('app_login'));
        }
        return new Response($this->environement->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]));
    }

    /**
     * @Route("/passwordrecovery", name="app_passwordrecovery")
     */
    public function PasswordRecovery(Request $request)
    {
        $type = PasswordRecoveryMailType::class;
        $form = $this->formResolverPasswordRecovery->getForm($request, $type);
        if ($form->isSubmitted() && $form->isValid()){
            $this->formResolverPasswordRecovery->treatment($form);
            return new RedirectResponse($this->router->generate('home'));
        }
        return new Response($this->environement->render('security/mailforpasswordrecovery.html.twig', [
            'form' => $form->createView()
        ]));
    }

    /**
     * @Route("/recoverypassword/{slug}", name="app_recoverypassword")
     */
    public function Changepassword(Request $request, UserPasswordEncoderInterface $encoder)
    {
        /** @var \App\Entity\User $user */
        $user = $this->manager->getRepository(User::class)->findOneBy(['token' => $request->attributes->get('slug')]);
        $type = ResetPasswordType::class;
        if(!empty($user)){
            $form = $this->formResolverRecoveryPassword->getForm($request, $type);
            if ($form->isSubmitted() && $form->isValid() && $user->getMail() == $form['email']->getData() ){
                $this->formResolverRecoveryPassword->treatment($form, $user);
                return new RedirectResponse($this->router->generate('home'));
            }
            return new Response($this->environement->render('security/resetpassword.html.twig', [
                'form' => $form->createView()
            ]));
        }
        else
            return new RedirectResponse($this->router->generate('home'));
    }
}
