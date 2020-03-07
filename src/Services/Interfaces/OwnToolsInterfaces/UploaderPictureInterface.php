<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : UploaderPictureInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Services\Interfaces\OwnTools;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploaderPictureInterface
{
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
    );

    /**
     * @param UploadedFile $uploadedFile
     * @param User $user
     */
    public function uploadProfilePicture(
        UploadedFile $uploadedFile,
        User $user
    );

    /**
     * @param UploadedFile $uploadedFile
     * @param User $user
     */
    public function updateProfilePicture(
        UploadedFile $uploadedFile,
        User $user
    );
}
