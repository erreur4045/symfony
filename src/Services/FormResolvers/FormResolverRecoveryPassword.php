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
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var FlashBagInterface  */
    private $bag;
    /** @var UserPasswordEncoderInterface  */
    private $encoder;
    /** @var FormFactoryInterface  */
    protected $formFactory;

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
        $this->bag->add('success', 'Votre mot de passe été modifié avec succès');
    }
}
