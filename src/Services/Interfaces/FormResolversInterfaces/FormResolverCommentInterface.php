<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : FormResolverCommentInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Services\Interfaces\FormResolversInterfaces;

use App\Entity\Comments;
use App\Entity\Figure;
use App\Entity\User;
use Symfony\Component\Form\FormInterface;

interface FormResolverCommentInterface
{
    /**
     * @param FormInterface $form
     * @param Comments $comment
     * @throws \Exception
     */
    public function updateCom(
        FormInterface $form,
        Comments $comment
    );

    /**
     * @param FormInterface $form
     * @param User $user
     * @param Figure $figure
     * @throws \Exception
     */
    public function addCom(
        FormInterface $form,
        User $user,
        Figure $figure
    );
}
