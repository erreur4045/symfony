<?php

namespace App\Services\OwnTools;

use App\Entity\Pictureslink;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

class UploaderPicture
{
    /** @var string  */
    private $pictureLinkDirectory;

    /** @var Filesystem  */
    private $filesystem;

    /** @var EntityManagerInterface  */
    private $manager;

    /**
     * UploaderPicture constructor.
     * @param string $pictureLinkDirectory
     * @param Filesystem $filesystem
     * @param EntityManagerInterface $manager
     */
    public function __construct(
        string $pictureLinkDirectory,
        Filesystem $filesystem,
        EntityManagerInterface $manager
    ) {
        $this->pictureLinkDirectory = $pictureLinkDirectory;
        $this->filesystem = $filesystem;
        $this->manager = $manager;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param User $user
     */
    public function uploadProfilePicture(UploadedFile $uploadedFile, User $user)
    {
        if ($uploadedFile) {
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate(
                'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
                $originalFilename
            );
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            try {
                $uploadedFile->move(
                    $this->pictureLinkDirectory,
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $user->setProfilePicture($newFilename);
        }
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param UserInterface $user
     */
    public function updateProfilePicture(UploadedFile $uploadedFile, UserInterface $user)
    {
        if (($user->getProfilePicture() != Pictureslink::PICTURELINKUSERRAND) == true) {
            $this->filesystem->remove(
                [
                    '',
                    '',
                    $this->pictureLinkDirectory . $user->getProfilePicture()
                ]
            );
        }
        if ($uploadedFile) {
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = transliterator_transliterate(
                'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
                $originalFilename
            );
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
            try {
                $uploadedFile->move(
                    $this->pictureLinkDirectory,
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $user->setProfilePicture($newFilename);
            $this->manager->persist($user);
            $this->manager->flush();
        }
    }
}
