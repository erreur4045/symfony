<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FormResolverRecoveryPassword extends FormResolver
{
    /**
     *
     *
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     *
     *
     * @var FlashBagInterface
     */
    private $bag;

    /**
     *
     *
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     *
     *
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(
        FormFactoryInterface $formFactory,
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        UrlGeneratorInterface $router,
        UserPasswordEncoderInterface $encoder
    ) {
        parent::__construct($formFactory);
        $this->manager = $manager;
        $this->bag = $bag;
        $this->router = $router;
        $this->encoder = $encoder;
    }

    public function treatment(FormInterface $form, User $user)
    {
        $hash = $this->encoder->encodePassword($user, $form['password']->getData());
        $user->setToken(null)->setPassword($hash);
        $this->manager->persist($user);
        $this->manager->flush();
        $this->bag->add('success', 'Votre mot de passe été modifié avec succès');
    }
}
