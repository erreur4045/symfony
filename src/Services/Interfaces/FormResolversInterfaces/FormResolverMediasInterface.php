<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : FormResolverMediasInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Services\Interfaces\FormResolversInterfaces;

use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Entity\User;
use Symfony\Component\Form\FormInterface;

interface FormResolverMediasInterface
{
    /**
     * @param FormInterface $form
     * @param User $user
     */
    public function updateProfilePicture(
        FormInterface $form,
        User $user
    );

    /**
     * @param FormInterface $form
     * @param Figure $figure
     * @param Pictureslink $exPicture
     */
    public function updatePictureTrick(
        FormInterface $form,
        Figure $figure,
        Pictureslink $exPicture
    );

    /**
     * @param FormInterface $form
     * @param Figure $figure
     * @param null $exVideo
     */
    public function updateVideoLink(
        FormInterface $form,
        Figure $figure,
        $exVideo = null
    );

    /**
     * @param FormInterface $form
     * @param Figure $figure
     */
    public function updateMedias(
        FormInterface $form,
        Figure $figure
    );
}
