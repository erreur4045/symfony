<?php

namespace App\Services\FormResolvers;

use App\Actions\Medias\Picture\PictureTools;
use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Entity\Videolink;
use App\Services\OwnTools\UploaderPicture;
use App\Traits\DoctrineTools;
use App\Traits\UploaderFileTools;
use App\Traits\ViewsTools;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class FormResolverMedias
 * @package App\Services\FormResolvers
 */
class FormResolverMedias extends FormResolver
{

    use PictureTools, DoctrineTools, ViewsTools, UploaderFileTools;

    private EntityManagerInterface $manager;
    private FlashBagInterface $bag;
    private UploaderPicture $uploaderPicture;
    private Filesystem $filesystem;
    private string $tricksPicturesDirectory;
    protected FormFactoryInterface $formFactory;

    /**
     * FormResolverMedias constructor.
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param UploaderPicture $uploaderPicture
     * @param Filesystem $filesystem
     * @param string $tricksPicturesDirectory
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        UploaderPicture $uploaderPicture,
        Filesystem $filesystem,
        string $tricksPicturesDirectory,
        FormFactoryInterface $formFactory
    ) {
        $this->manager = $manager;
        $this->bag = $bag;
        $this->uploaderPicture = $uploaderPicture;
        $this->filesystem = $filesystem;
        $this->tricksPicturesDirectory = $tricksPicturesDirectory;
        $this->formFactory = $formFactory;
        parent::__construct($formFactory);
    }


    /**
     * @param FormInterface $form
     * @param UserInterface $user
     */
    public function updateProfilePicture(FormInterface $form, UserInterface $user)
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $form['profilePicture']->getData();
        $this->uploaderPicture->updateProfilePicture($uploadedFile, $user);
        $this->pushInDataBase($user);
        $this->displayMessage('success', 'Votre avatar a été modifié');
    }

    /**
     * @param FormInterface $form
     * @param Figure $figure
     * @param Pictureslink $expiredFile
     */
    public function updateOnePicture(FormInterface $form, Figure $figure, Pictureslink $expiredFile = null)
    {
        // todo : swich case USER and Figure
        /** @var Pictureslink $newFile */
        $newFile = $form->getData();
        $newFile->setFigure($figure);
        $this->setIsFirstImage($expiredFile, $newFile);

        /** @var Pictureslink $uploadedFile */
        $uploadedFile = $this->getDataFromField($form, 'picture');

        if ($uploadedFile) {
            $this->removeExpireFile($expiredFile);
            $newFilename = $this->assignValidName($uploadedFile);
            try {
                $this->moveFileOnPath($uploadedFile, $newFilename);
            } catch (FileException $e) {
            }
            $newFile->setLinkpictures($newFilename);
            $this->pushInDataBase($newFile);
        }
    }

    /**
     * @param FormInterface $form
     * @param Figure $figure
     * @param null $exVideo
     */
    public function updateVideoLink(FormInterface $form, Figure $figure, $exVideo = null)
    {
        /** @var Videolink $newVideo */
        $newVideo = $form->getData();
        $newVideoLink = $this->getDataFromField($form, 'linkvideo');
        $linkToStock = $this->getEmbedFromYoutubeLink($newVideoLink);
        $newVideo->setFigure($figure);
        $newVideo->setLinkvideo($linkToStock);
        $this->removeFromDataBase($exVideo);
        $this->pushInDataBase($newVideo);
    }

    /**
     * @param FormInterface $form
     * @param Figure $figure
     */
    public function updateMedias(FormInterface $form, Figure $figure)
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $this->getDataFromField($form, 'pictureslinks');
        /** @var Pictureslink $fileToUpload */
        foreach ($uploadedFile as $fileToUpload) {
            if ($fileToUpload) {
                $fileToUpload->setFigure($figure);
                $newFilename = $this->assignValidName($fileToUpload);
                try {
                    $this->moveFileOnPath($fileToUpload, $newFilename);
                } catch (FileException $e) {
                }
                $fileToUpload->setLinkpictures($newFilename);
                $this->pushInDataBase($fileToUpload);
            }
        }
        /** @var Videolink[] $newVideo */
        $newVideoLink = $this->getDataFromField($form, 'videolinks');
        /** @var Videolink $linkToUpload */
        foreach ($newVideoLink as $linkToUpload) {
            $linkToStock = $this->getEmbedFromYoutubeLink($linkToUpload);
            $linkToUpload->setFigure($figure);
            $linkToUpload->setLinkvideo($linkToStock);
            $this->pushInDataBase($linkToUpload);
        }
    }

    protected function getEmbedFromYoutubeLink(Videolink $newVideoLink)
    {
        preg_match(
            Videolink::PATTERNYT,
            $newVideoLink->getLinkvideo(),
            $matches
        );
        return Videolink::HTTPS_YOUTUBE_EMBED . $matches[1];
    }
}
