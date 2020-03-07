<?php

namespace App\Actions\Dashboard;

use App\Actions\Interfaces\Dashboard\GetDashboardInterface;
use App\Entity\Figure;
use App\Entity\User;
use App\Form\ProfilePictureType;
use App\Services\FormResolvers\FormResolverMedias;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

/**
 * @Route("/dashboard", name="app_dashboard")
 * @IsGranted("ROLE_USER")
 */
class GetDashboard implements GetDashboardInterface
{
    /** @var Environment  */
    private $environment;
    /** @var FormResolverMedias  */
    private $formResolverMedias;
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var TokenStorageInterface  */
    private $tokenStorage;
    /** @var UrlGeneratorInterface  */
    private $router;

    /**
     * GetDashboard constructor.
     * @param Environment $environment
     * @param FormResolverMedias $formResolverMedias
     * @param EntityManagerInterface $manager
     * @param TokenStorageInterface $tokenStorage
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        Environment $environment,
        FormResolverMedias $formResolverMedias,
        EntityManagerInterface $manager,
        TokenStorageInterface $tokenStorage,
        UrlGeneratorInterface $router
    ) {
        $this->environment = $environment;
        $this->formResolverMedias = $formResolverMedias;
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(Request $request)
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
