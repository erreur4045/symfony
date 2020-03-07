<?php

namespace App\Services\FormResolvers;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class FormResolver
{
    /** @var FormFactoryInterface  */
    protected $formFactory;

    /**
     * FormResolver constructor.
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }


    public function getForm(Request $request, string $classType, $data = null): FormInterface
    {
        return $this->formFactory->create($classType, $data)->handleRequest($request);
    }
}
