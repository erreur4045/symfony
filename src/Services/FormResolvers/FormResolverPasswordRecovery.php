<?php

namespace App\Services\FormResolvers;

use App\Entity\User;
use App\Services\Interfaces\FormResolversInterfaces\FormResolverPasswordRecoveryInterface;
use App\Services\OwnTools\MailSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FormResolverPasswordRecovery extends FormResolver implements FormResolverPasswordRecoveryInterface
{
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var FlashBagInterface  */
    private $bag;
    /** @var UrlGeneratorInterface  */
    private $router;
    /** @var MailSender  */
    private $mailSender;
    /** @var FormFactoryInterface  */
    protected $formFactory;

    /**
     * FormResolverPasswordRecovery constructor.
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param UrlGeneratorInterface $router
     * @param MailSender $mailSender
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        UrlGeneratorInterface $router,
        MailSender $mailSender,
        FormFactoryInterface $formFactory
    ) {
        $this->manager = $manager;
        $this->bag = $bag;
        $this->router = $router;
        $this->mailSender = $mailSender;
        $this->formFactory = $formFactory;
        parent::__construct($formFactory);
    }


    /**
     * @param FormInterface $form
     * @return RedirectResponse
     */
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
