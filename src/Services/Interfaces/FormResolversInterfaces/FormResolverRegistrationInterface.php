<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : FormResolverRegistrationInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Services\Interfaces\FormResolversInterfaces;

use Symfony\Component\Form\FormInterface;

interface FormResolverRegistrationInterface
{
    /**
     * @param FormInterface $form
     * @throws \Exception
     */
    public function treatment(FormInterface $form);
}
