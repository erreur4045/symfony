<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FormResolverPasswordRecovery extends FormResolver
{
   /** @var EntityManagerInterface  */
    private $manager;

    /** @var FlashBagInterface  */
    private $bag;

    /** @var MailSender  */
    private $mailSender;

    /** @var UrlGeneratorInterface  */
    private $router;

    public function __construct(
        FormFactoryInterface $formFactory,
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        UrlGeneratorInterface $router,
        MailSender $mailSender
    ) {
        parent::__construct($formFactory);
        $this->manager = $manager;
        $this->bag = $bag;
        $this->mailSender = $mailSender;
        $this->router = $router;
    }

    public function treatment(FormInterface $form)
    {
        $data = $form->getData();
        /** @var User $user */
        $user = $this->manager->getRepository(User::class)
            ->findOneBy(['name' => $data['pseudo'], 'mail' => $data['email']]);
        if (empty($user)) {
            $this->bag->add('success', 'Un mail vous a été envoyé avec un lien pour modifier votre mot de passe');

            return new RedirectResponse($this->router->generate('home'));
        }
        $token = md5(uniqid(rand()));
        $user->setToken($token);
        $this->manager->persist($user);
        $this->manager->flush();
        $this->mailSender->sendEmailWithToken($token, $data['email']);
        $this->bag->add('success', 'Un mail vous a été envoyé avec un lien pour modifier votre mot de passe');
    }
}
