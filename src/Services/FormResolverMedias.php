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
        $newPicture->setFigure($figure);
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

    public function updateVideoLink(FormInterface $form, Figure $figure, $exVideo = null)
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

    public function updateMedias(FormInterface $form, Figure $figure)
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $form['pictureslinks']->getData();
        foreach ($uploadedFile as $fileToUpload) {
            if ($fileToUpload) {
                $originalFilename = pathinfo($fileToUpload->getPicture()->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate(
                    'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
                    $originalFilename
                );
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $fileToUpload->getPicture()->guessExtension();
                try {
                    $fileToUpload->getPicture()->move(
                        $this->tricksPicturesDirectory,
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                /** @var Pictureslink $fileToUpload */
                $fileToUpload->setFigure($figure);
                $fileToUpload->setLinkpictures($newFilename);
                $this->manager->persist($fileToUpload);
            }
            $this->manager->flush();
        }
        /** @var Videolink $newVideo */
        $newVideoLink = $form['videolinks']->getData();
        foreach ($newVideoLink as $linkToUpload) {
            $videoEmbed = preg_match(
                '/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((?:\w|-){11})(?:&list=(\S+))?$/',
                $linkToUpload->getLinkvideo(),
                $matches
            );
            $linkToStock = 'https://www.youtube.com/embed/' . $matches[1];
            $linkToUpload->setFigure($figure);
            $linkToUpload->setLinkvideo($linkToStock);
            $this->manager->persist($linkToUpload);
            $this->manager->flush();
        }
        $this->manager->persist($figure);
    }
}
