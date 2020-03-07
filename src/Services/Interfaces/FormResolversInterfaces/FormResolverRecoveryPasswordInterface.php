<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : FormResolverRecoveryPasswordInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Services\Interfaces\FormResolversInterfaces;

use App\Entity\User;
use Symfony\Component\Form\FormInterface;

interface FormResolverRecoveryPasswordInterface
{
    /**
     * @param FormInterface $form
     * @param User $user
     */
    public function treatment(
        FormInterface $form,
        User $user
    );
}
