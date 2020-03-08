<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : FormResolverPasswordRecoveryInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Services\Interfaces\FormResolversInterfaces;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

interface FormResolverPasswordRecoveryInterface
{
    /**
     * @param FormInterface $form
     * @return RedirectResponse
     */
    public function treatment(FormInterface $form);
}
