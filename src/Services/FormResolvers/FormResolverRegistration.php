<?php

namespace App\Services\FormResolvers;

use App\Entity\Pictureslink;
use App\Entity\User;
use App\Services\Interfaces\FormResolversInterfaces\FormResolverRegistrationInterface;
use App\Services\OwnTools\UploaderPicture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FormResolverRegistration extends FormResolver implements FormResolverRegistrationInterface
{
    private EntityManagerInterface $manager;
    private FlashBagInterface $bag;
    private UserPasswordEncoderInterface $encoder;
    private UploaderPicture $uploaderPicture;
    protected FormFactoryInterface $formFactory;

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
    ) {
        $this->manager = $manager;
        $this->bag = $bag;
        $this->encoder = $encoder;
        $this->uploaderPicture = $uploaderPicture;
        $this->formFactory = $formFactory;
        parent::__construct($formFactory);
    }


    /**
     * @param FormInterface $form
     * @throws \Exception
     */
    public function treatment(FormInterface $form)
    {
        /** @var User $user */
        $user = $form->getData();

        $hash = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($hash);
        $user->setDatesub(new \DateTime());

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $form['profilePicture']->getData();
        if (empty($uploadedFile)) {
            $user->setProfilePicture(Pictureslink::PICTURELINKUSERRAND);
        } else {
            $this->uploaderPicture->uploadProfilePicture($uploadedFile, $user);
        }
        $this->manager->persist($user);
        $this->manager->flush();
        $this->bag->add('success', 'Votre inscription est validée');
    }
}
