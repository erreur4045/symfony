<?php

namespace App\Services\FormResolvers;

use App\Entity\User;
use App\Services\Interfaces\FormResolversInterfaces\FormResolverRecoveryPasswordInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FormResolverRecoveryPassword extends FormResolver implements FormResolverRecoveryPasswordInterface
{
    private EntityManagerInterface $manager;
    private FlashBagInterface $bag;
    private UserPasswordEncoderInterface $encoder;
    protected FormFactoryInterface $formFactory;

    /**
     * FormResolverRecoveryPassword constructor.
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param UserPasswordEncoderInterface $encoder
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        UserPasswordEncoderInterface $encoder,
        FormFactoryInterface $formFactory
    ) {
        $this->manager = $manager;
        $this->bag = $bag;
        $this->encoder = $encoder;
        $this->formFactory = $formFactory;
        parent::__construct($formFactory);
    }


    /**
     * @param FormInterface $form
     * @param User $user
     */
    public function treatment(FormInterface $form, User $user)
    {
        $hash = $this->encoder->encodePassword($user, $form['password']->getData());
        $user->setToken(null)->setPassword($hash);
        $this->manager->persist($user);
        $this->manager->flush();
        $this->bag->add('success', 'Votre mot de passe a été modifié avec succès');
    }
}
