<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : FormResolverInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Services\Interfaces\FormResolvers;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface FormResolverInterface
{
    /**
     * FormResolver constructor.
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory);

    /**
     * @param Request $request
     * @param string $classType
     * @param null $data
     * @return FormInterface
     */
    public function getForm(
        Request $request,
        string $classType,
        $data = null
    ): FormInterface;
}
