<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : LogoutController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Security;


use App\Actions\OwnAbstractController;
use App\Form\RegistrationType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends OwnAbstractController
{
    /**
     * @Route("/registration", name="registration")
     */
    public function registration(Request $request)
    {
        /** @var Form $form */
        $form = $this->fromResolverRegistration->getForm($request, RegistrationType::class);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->fromResolverRegistration->treatment($form);
            return new RedirectResponse($this->router->generate('app_login'));
        }

        return new Response(
            $this->templating->render(
                'security/registration.html.twig',
                [
                    'form' => $form->createView()
                ]
            )
        );
    }
}