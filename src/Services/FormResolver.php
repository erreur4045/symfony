<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

abstract class FormResolver
{
    /** @var FormFactoryInterface  */
    protected $formFactory;

    /** @var EntityManagerInterface  */
    protected $manager;

    /** @var FlashBagInterface  */
    protected $bag;

    /** @var UrlGeneratorInterface  */
    protected $router;

    /** @var string  */
    protected $tricksPicturesDirectory;

    /** @var string  */
    protected $pictureLinkDirectory;

    /** @var UploaderPicture  */
    protected $uploaderPicture;

    /** @var Filesystem  */
    protected $filesystem;

    /** @var UserPasswordEncoderInterface  */
    protected $encoder;

    /** @var MailSender  */
    protected $mailSender;

    /**
     * FormResolver constructor.
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param UrlGeneratorInterface $router
     * @param string $tricksPicturesDirectory
     * @param string $pictureLinkDirectory
     * @param UploaderPicture $uploaderPicture
     * @param Filesystem $filesystem
     * @param UserPasswordEncoderInterface $encoder
     * @param MailSender $mailSender
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        UrlGeneratorInterface $router,
        string $tricksPicturesDirectory,
        string $pictureLinkDirectory,
        UploaderPicture $uploaderPicture,
        Filesystem $filesystem,
        UserPasswordEncoderInterface $encoder,
        MailSender $mailSender
    ) {
        $this->formFactory = $formFactory;
        $this->manager = $manager;
        $this->bag = $bag;
        $this->router = $router;
        $this->tricksPicturesDirectory = $tricksPicturesDirectory;
        $this->pictureLinkDirectory = $pictureLinkDirectory;
        $this->uploaderPicture = $uploaderPicture;
        $this->filesystem = $filesystem;
        $this->encoder = $encoder;
        $this->mailSender = $mailSender;
    }

    public function getForm(Request $request, string $classType, $data = null): FormInterface
    {
        return $this->formFactory->create($classType, $data)->handleRequest($request);
    }
}
