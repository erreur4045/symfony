<?php


namespace App\Services;


use App\Entity\User;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderPicture
{
    /** @var string */
    private $pictureLinkDirectory;

    public function __construct(
        string $pictureLinkDirectory)
    {
        $this->pictureLinkDirectory = $pictureLinkDirectory;
    }

    public function uploadProfilePicture(UploadedFile $uploadedFile, User $user)
    {
        if ($uploadedFile) {
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
                $originalFilename);
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
}