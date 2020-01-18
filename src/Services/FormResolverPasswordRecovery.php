<?php


namespace App\Services;


use Symfony\Component\Form\FormFactoryInterface;

class FormResolverPasswordRecovery extends FormResolver
{
    public function __construct(FormFactoryInterface $formFactory)
    {
        parent::__construct($formFactory);
    }

}