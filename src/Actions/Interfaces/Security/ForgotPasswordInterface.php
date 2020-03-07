<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : ForgotPasswordInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Interfaces\Security;


use App\Services\FormResolvers\FormResolverPasswordRecovery;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * @Route("/forgot_password", name="app_passwordrecovery")
 */
interface ForgotPasswordInterface
{
    /**
     * ForgotPassword constructor.
     * @param Environment $templating
     * @param FormResolverPasswordRecovery $formResolverPasswordRecovery
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        Environment $templating,
        FormResolverPasswordRecovery $formResolverPasswordRecovery,
        UrlGeneratorInterface $router
    );

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(Request $request);
}