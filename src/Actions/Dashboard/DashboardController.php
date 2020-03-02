<?php

namespace App\Actions\Dashboard;

use App\Actions\OwnAbstractController;
use App\Entity\Figure;
use App\Entity\User;
use App\Form\ProfilePictureType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends OwnAbstractController
{
    /**
     * @Route("/dashboard", name="app_dashboard")
     * @IsGranted("ROLE_USER")
     */

    public function index(Request $request)
    {
        /** @var Figure $figures */
        $figures = $this->manager->getRepository(Figure::class)
            ->findBy(
                ['user' => $this->tokenStorage->getToken()->getUser()->getId()]
            );

        /** @var User $userData */
        $userData = $this->tokenStorage->getToken()->getUser();

        $type = ProfilePictureType::class;
        $form = $this->formResolverMedias->getForm($request, $type);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->formResolverMedias->updateProfilePicture($form, $userData);
            return new RedirectResponse($this->router->generate('app_dashboard'));
        }
        return new Response(
            $this->environment->render(
                'dashboard/index.html.twig',
                [
                    'form' => $form->createView(),
                    'controller_name' => 'Mon Dashboard',
                    'title' => 'Mon Dashboard',
                    'figure' => $figures,
                    'image' => $userData->getProfilePicture()
                ]
            )
        );
    }
}
