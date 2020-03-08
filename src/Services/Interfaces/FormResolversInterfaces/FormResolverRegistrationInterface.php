<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : FormResolverRegistrationInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Services\Interfaces\FormResolversInterfaces;

use App\Services\OwnTools\UploaderPicture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

interface FormResolverRegistrationInterface
{
    /**
     * FormResolverRegistration constructor.
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param UserPasswordEncoderInterface $encoder
     * @param UploaderPicture $uploaderPicture
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        UserPasswordEncoderInterface $encoder,
        UploaderPicture $uploaderPicture,
        FormFactoryInterface $formFactory
    );
    
    /**
     * @param FormInterface $form
     * @throws \Exception
     */
    public function treatment(FormInterface $form);
}
