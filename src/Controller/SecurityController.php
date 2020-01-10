<?php

namespace App\Controller;

use App\Entity\Pictureslink;
use App\Entity\ProfilePicture;
use App\Entity\User;
use App\Form\PasswordRecoveryMailType;
use App\Form\RegistrationType;
use App\Form\ResetPasswordType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Provider\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

class SecurityController extends AbstractController
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

    public function __construct(
        Environment $environment,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $generator,
        FlashBagInterface $bag,
        EntityManagerInterface $manager
    ) {
        $this->environement = $environment;
        $this->formFactory = $formFactory;
        $this->generator = $generator;
        $this->bag = $bag;
        $this->manager = $manager;

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
        $form = $this->formFactory->create(RegistrationType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setDatesub(new \DateTime());
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['profilePicture']->getData();
            if ($uploadedFile) {
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
                    $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
                try {
                    $uploadedFile->move(
                        $this->getParameter('picture_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $user->setProfilePicture($newFilename);
            }
            $manager->persist($user);
            $manager->flush();

            $this->bag->add('success', 'Votre inscription est ok');
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
        dump($user);
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
