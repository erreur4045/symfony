<?php


namespace App\Services;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class FormResolver
{
    /** @var FormFactoryInterface $formFactory */
    protected $formFactory;

    public function __construct(
        FormFactoryInterface $formFactory
    ) {
        $this->formFactory = $formFactory;
    }

    public function getForm(Request $request, string $classType): FormInterface
    {
        return $this->formFactory->create($classType)->handleRequest($request);
    }

}