<?php

namespace App\Actions\Dashboard;

use App\Entity\Figure;
use App\Entity\User;
use App\Form\ProfilePictureType;
use App\Repository\FigureRepository;
use App\Services\FormResolvers\FormResolverMedias;
use App\Traits\DashboardTools;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @Route("/dashboard", name="app_dashboard")
 * @IsGranted("ROLE_USER")
 */
class GetDashboard
{
    use DashboardTools;

    const DASHBOARD_TWIG_PATH = 'dashboard/index.html.twig';
    const PROFILE_PICTURE_FORM = ProfilePictureType::class;
    const ROUTE_NAME = 'app_dashboard';

    /** @var Environment  */
    private $environment;
    /** @var TokenStorageInterface  */
    private $tokenStorage;
    /** @var FormResolverMedias  */
    private $formResolverMedias;
    /** @var UrlGeneratorInterface  */
    private $router;
    /** @var FigureRepository */
    private $trickRepo;

    /**
     * GetDashboard constructor.
     * @param Environment $environment
     * @param TokenStorageInterface $tokenStorage
     * @param FormResolverMedias $formResolverMedias
     * @param UrlGeneratorInterface $router
     * @param FigureRepository $trickRepo
     */
    public function __construct(
        Environment $environment,
        TokenStorageInterface $tokenStorage,
        FormResolverMedias $formResolverMedias,
        UrlGeneratorInterface $router,
        FigureRepository $trickRepo
    ) {
        $this->environment = $environment;
        $this->tokenStorage = $tokenStorage;
        $this->formResolverMedias = $formResolverMedias;
        $this->router = $router;
        $this->trickRepo = $trickRepo;
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(Request $request)
    {
        /** @var User $connectedUser */
        $connectedUser = $this->getConnectedUser();
        /** @var Figure[] $figures */
        $figures = $this->trickRepo->getTricksFromUser($connectedUser);
        /** @var FormInterface $form */
        $form = $this->getForm($request);

        if ($this->isConform($form)) {
            $this->updateProfilePicture($form);
            return $this->getRedirect(self::ROUTE_NAME);
        }

        $contextView = [
            'form' => $form->createView(),
            'controller_name' => 'Mon Dashboard',
            'title' => 'Mon Dashboard',
            'figure' => $figures,
            'image' => $this->getProfilePicture()
        ];

        return new Response(
            $this->environment->render(
                self::DASHBOARD_TWIG_PATH,
                $contextView
            )
        );
    }
}
