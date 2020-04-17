<?php


namespace App\Responder\Interfaces;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

interface ResponderInterface
{
    public function render(string $name, array $context = []):Response;

    public function redirect(string $name, array $context = []):RedirectResponse;
}