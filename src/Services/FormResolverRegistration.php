<?php

namespace App\Services;

use App\Entity\Pictureslink;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FormResolverRegistration extends FormResolver
{
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
        $this->bag->add('success', 'Votre inscription est ok');
    }
}
