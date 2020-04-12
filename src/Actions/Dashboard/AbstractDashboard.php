<?php


namespace App\Actions\Dashboard;


use App\Entity\Figure;
use App\Entity\User;
use App\Repository\FigureRepository;
use App\Services\FormResolvers\FormResolverMedias;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;

abstract class AbstractDashboard
{

    /** @var Environment  */
    private $environment;
    /** @var FormResolverMedias  */
    private $formResolverMedias;
    /** @var TokenStorageInterface  */
    private $tokenStorage;
    /** @var UrlGeneratorInterface  */
    private $router;
    /** @var FigureRepository */
    private $trickRepo;

    /**
     * AbstractDashbord constructor.
     * @param Environment $environment
     * @param FormResolverMedias $formResolverMedias
     * @param TokenStorageInterface $tokenStorage
     * @param UrlGeneratorInterface $router
     * @param FigureRepository $trickRepo
     */
    public function __construct(
        Environment $environment,
        FormResolverMedias $formResolverMedias,
        TokenStorageInterface $tokenStorage,
        UrlGeneratorInterface $router,
        FigureRepository $trickRepo
    ) {
        $this->environment = $environment;
        $this->formResolverMedias = $formResolverMedias;
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
        $this->trickRepo = $trickRepo;
    }


    /**
     * @return string|UserInterface
     */
    public function getConnectedUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }

    /**
     * @param User $connectedUser
     * @return Figure[]
     */
    public function getTricksFromUser(User $connectedUser): array
    {
        return $this->trickRepo->findBy(['user' => $connectedUser->getId()]);
    }

    /**
     * @param FormInterface $form
     * @return bool
     */
    public function isConform(FormInterface $form): bool
    {
        return $form->isSubmitted() && $form->isValid();
    }

    /**
     * @param Request $request
     * @return FormInterface
     */
    public function getForm(Request $request): FormInterface
    {
        return $this->formResolverMedias->getForm($request, GetDashboard::PROFILE_PICTURE_FORM);
    }

    /**
     * @param FormInterface $form
     * @param User $connectedUser
     */
    public function updateProfilePicture(
        FormInterface $form,
        User $connectedUser
    ) {
        return $this->formResolverMedias->updateProfilePicture($form, $connectedUser);
    }

    /**
     * @param $routeName
     * @return RedirectResponse
     */
    public function getRedirect($routeName): RedirectResponse
    {
        return new RedirectResponse($this->router->generate($routeName));
    }
}