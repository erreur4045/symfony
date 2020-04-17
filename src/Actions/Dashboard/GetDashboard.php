<?php

namespace App\Actions\Dashboard;

use App\Entity\User;
use App\Form\ProfilePictureType;
use App\Repository\FigureRepository;
use App\Responder\Interfaces\ResponderInterface;
use App\Services\FormResolvers\FormResolverMedias;
use App\Traits\DashboardTools;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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

    private TokenStorageInterface $tokenStorage;
    private FormResolverMedias $formResolverMedias;
    private FigureRepository $trickRepo;
    private ResponderInterface $responder;

    /**
     * GetDashboard constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param FormResolverMedias $formResolverMedias
     * @param FigureRepository $trickRepo
     * @param ResponderInterface $responder
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        FormResolverMedias $formResolverMedias,
        FigureRepository $trickRepo,
        ResponderInterface $responder
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->formResolverMedias = $formResolverMedias;
        $this->trickRepo = $trickRepo;
        $this->responder = $responder;
    }


    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function __invoke(Request $request)
    {
        /** @var User $connectedUser */
        $connectedUser = $this->getConnectedUser();
        $figures = $this->trickRepo->getTricksFromUser($connectedUser);
        $form = $this->getForm($request);

        if ($this->isConform($form)) {
            $this->updateProfilePicture($form);
            return $this->responder->redirect(self::ROUTE_NAME);
        }

        $contextView = [
            'form' => $form->createView(),
            'controller_name' => 'Mon Dashboard',
            'title' => 'Mon Dashboard',
            'figure' => $figures,
            'image' => $this->getProfilePicture()
        ];
        return $this->responder->render(self::DASHBOARD_TWIG_PATH, $contextView);
    }
}
