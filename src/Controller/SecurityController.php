<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordRecoveryMailType;
use App\Form\RegistrationType;
use App\Form\ResetPasswordType;
use App\Services\FormResolver;
use App\Services\FormResolverRegistration;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
//todo : sortir de l'abstract controller
class SecurityController
{
    /**
     * Desciption :
     *
     * @var Environment
     */
    private $environement;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var UrlGeneratorInterface */
    private $generator;

    /** @var FlashBagInterface */
    private $bag;

    /** @var EntityManagerInterface */
    private $manager;

    /** @var FormResolver $formResolver */
    private $formResolver;

    public function __construct(
        Environment $environment,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $generator,
        FlashBagInterface $bag,
        EntityManagerInterface $manager,
        FormResolverRegistration $formResolverRegistration
    ) {
        $this->environement = $environment;
        $this->formFactory = $formFactory;
        $this->generator = $generator;
        $this->bag = $bag;
        $this->manager = $manager;
        $this->fromResolverRegistration = $formResolverRegistration;

    }

    /**
     * @Route("/login", name="app_login")
     * @return Response
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
    public function PasswordRecovery(Request $request, MailController $mailController)
    {
        $form = $this->createForm(PasswordRecoveryMailType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            /** @var \App\Entity\User $user */
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['name' => $data['pseudo'], 'mail' => $data['email']]);
            if (empty($user)){
                $this->bag->add('success', 'Un mail vous a été envoyé avec un lien pour modifier votre mot de passe');
                return new RedirectResponse($this->generateUrl('home'));
            }
            $token = md5(uniqid(rand()));
            $user->setToken($token);
            $this->manager->persist($user);
            $this->manager->flush();
            //todo : montrer à Aurel si l'injection est ok
            $mailController->sendEmailWithToken($token);
            $this->bag->add('success', 'Un mail vous a été envoyé avec un lien pour modifier votre mot de passe');
            return new RedirectResponse($this->generateUrl('home'));
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
        if(!empty($user)){
            $form = $this->createForm(ResetPasswordType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid() && $user->getMail() == $form['email']->getData() ){
                $hash = $encoder->encodePassword($user, $form['password']->getData());
                $user->setToken(null)->setPassword($hash);
                $this->manager->persist($user);
                $this->manager->flush();
                $this->bag->add('success', 'Votre mot de passe été modifié avec succès');
                return new RedirectResponse($this->generateUrl('home'));
            }
            return new Response($this->environement->render('security/resetpassword.html.twig', [
                'form' => $form->createView()
            ]));
        }
        else
            return new RedirectResponse($this->generateUrl('home'));
    }
}
