<?php


namespace App\Services;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FormResolverUploadPicture extends FormResolver
{
    /** @var UserPasswordEncoderInterface */
    protected $encoder;
    /** @var EntityManagerInterface */
    private $manager;
    /** @var FlashBagInterface */
    private $bag;
    /** @var UploaderPicture */
    private $uploaderPicture;

    public function __construct(
        UserPasswordEncoderInterface $encoder,
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        FormFactoryInterface $formFactory,
        UploaderPicture $uploaderPicture
    ) {
        parent::__construct($formFactory);
        $this->encoder = $encoder;
        $this->bag = $bag;
        $this->manager = $manager;
        $this->uploaderPicture = $uploaderPicture;
    }

    public function treatment(FormInterface $form, User $user)
    {

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $form['profilePicture']->getData();
        $this->uploaderPicture->updateProfilePicture($uploadedFile, $user);

        $this->manager->persist($user);
        $this->manager->flush();
        $this->bag->add('success', 'Votre avatar a été modifié');
    }
}