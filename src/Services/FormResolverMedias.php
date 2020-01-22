<?php

namespace App\Services;

use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Entity\User;
use App\Entity\Videolink;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FormResolverMedias extends FormResolver
{
    /**
     *
     *
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

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
     * @var UploaderPicture
     */
    private $uploaderPicture;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $tricksPicturesDirectory;

    public function __construct(
        UserPasswordEncoderInterface $encoder,
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        FormFactoryInterface $formFactory,
        UploaderPicture $uploaderPicture,
        Filesystem $filesystem,
        string $tricksPicturesDirectory
    ) {
        parent::__construct($formFactory);
        $this->filesystem = $filesystem;
        $this->encoder = $encoder;
        $this->bag = $bag;
        $this->manager = $manager;
        $this->uploaderPicture = $uploaderPicture;
        $this->tricksPicturesDirectory = $tricksPicturesDirectory;
    }

    public function updateProfilePicture(FormInterface $form, User $user)
    {

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $form['profilePicture']->getData();
        $this->uploaderPicture->updateProfilePicture($uploadedFile, $user);

        $this->manager->persist($user);
        $this->manager->flush();
        $this->bag->add('success', 'Votre avatar a été modifié');
    }

    public function updatePictureTrick(FormInterface $form, $user, Figure $figure, Pictureslink $exPicture)
    {
        /** @var Pictureslink $newPicture */
        $newPicture = $form->getData();
        $newPicture->setUser($user)->setFigure($figure);
        if ($exPicture->getFirstImage() == true) {
            $newPicture->setFirstImage(true);
        }
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $form['picture']->getData();

        if ($uploadedFile) {
            $this->filesystem->remove(
                [
                '',
                '',
                $this->tricksPicturesDirectory . $exPicture
                ]
            );
            $this->manager->remove($exPicture);
            $this->manager->flush();
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate(
                'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
                $originalFilename
            );
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            try {
                $uploadedFile->move(
                    $this->tricksPicturesDirectory,
                    $newFilename
                );
            } catch (FileException $e) {
            }
            $newPicture->setLinkpictures($newFilename);
            $this->manager->persist($newPicture);
            $this->manager->flush();
        }
    }

    public function updateVideoLink(FormInterface $form, Figure $figure, $exVideo)
    {
        /** @var Videolink $newVideo */
        $newVideo = $form->getData();
        $newVideoLink = $form['linkvideo']->getData();
        $videoEmbed = preg_match(
            '/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((?:\w|-){11})(?:&list=(\S+))?$/',
            $newVideoLink,
            $matches
        );
        $linkToStock = 'https://www.youtube.com/embed/' . $matches[1];
        $newVideo->setFigure($figure);
        $newVideo->setLinkvideo($linkToStock);
        $this->manager->remove($exVideo);
        $this->manager->persist($newVideo);
        $this->manager->flush();
    }
}
