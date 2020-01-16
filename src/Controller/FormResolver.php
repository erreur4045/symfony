<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FormResolver
{
    /** @var FormFactoryInterface $formFactory */
    protected $formFactory;
    /** @var UserPasswordEncoderInterface */
    protected $encoder;
    /** @var EntityManagerInterface */
    private $manager;
    /** @var FlashBagInterface */
    private $bag;

    /** @var ContainerBagInterface */
    private $containerBag;

    /** @var UrlGeneratorInterface */
    private $generator;

    public function __construct(
        FormFactoryInterface $formFactory,
        UserPasswordEncoderInterface $encoder,
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        ContainerBagInterface $containerBag,
        UrlGeneratorInterface $generator
    ) {
        $this->generator = $generator;
        $this->formFactory = $formFactory;
        $this->encoder = $encoder;
        $this->bag = $bag;
        $this->containerBag = $containerBag;
        $this->manager = $manager;
    }

    public function getForm(Request $request): FormInterface
    {
        return $this->formFactory->create(RegistrationType::class)->handleRequest($request);
    }

    public function treatment(FormInterface $form)
    {
        /** @var User $user */
        $user = $form->getData();
        $hash = $this->encoder->encodePassword($user, $user->getPassword());
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
                    $this->containerBag->get('picture_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $user->setProfilePicture($newFilename);
        }
        $this->manager->persist($user);
        $this->manager->flush();
        $this->bag->add('success', 'Votre inscription est ok');
        return new RedirectResponse($this->generator->generate('app_login'));
    }
}