<?php

namespace App\Actions\Dashboard;

use App\Actions\OwnAbstractController;
use App\Entity\Figure;
use App\Entity\User;
use App\Form\ProfilePictureType;
use App\Repository\FigureRepository;
use App\Repository\UserRepository;
use App\Services\FormResolverMedias;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends OwnAbstractController
{
    /**
     * @Route("/dashboard", name="app_dashboard")
     */

    public function index(UserInterface $user = null, Request $request)
    {
        if ($user == null) {
            return new Response(
                $this->environment->render(
                    'block_for_include/no_connect.html.twig',
                    [
                    ]
                )
            );
        }

        /** @var Figure $figures */
        $figures = $this->manager->getRepository(Figure::class)->findBy(['user' => $user->getId()]);

        /** @var User $userData */
        $userData = $this->tokenStorage->getToken()->getUser();

        if ($user->getName() == $this->tokenStorage->getToken()->getUser()) {
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
                    'user' => $user,
                    'controller_name' => 'Mon Dashboard',
                    'title' => 'Mon Dashboard',
                    'figure' => $figures,
                    'image' => $userData->getProfilePicture()
                    ]
                )
            );
        }
        return new RedirectResponse($this->router->generate('home'));
    }
}
