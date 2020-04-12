<?php


namespace App\Actions\Dashboard;


use App\Entity\Figure;
use App\Repository\FigureRepository;
use App\Services\FormResolvers\FormResolverMedias;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;

trait DashboardTools
{
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
     * @return string|UserInterface
     */
    public function getConnectedUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }

    /**
     * @return Figure[]
     */
    public function getTricksFromUser(): array
    {
        $user = $this->getConnectedUser();
        return $this->trickRepo->findBy(['user' => $user->getId()]);
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
     */
    public function updateProfilePicture(FormInterface $form)
    {
        $user = $this->getConnectedUser();
        $this->formResolverMedias->updateProfilePicture($form, $user);
    }

    /**
     * @param $routeName
     * @return RedirectResponse
     */
    public function getRedirect($routeName): RedirectResponse
    {
        return new RedirectResponse($this->router->generate($routeName));
    }

    /**
     * @return string
     */
    public function getProfilePicture(): string
    {
        return $this->getConnectedUser()->getProfilePicture();
    }
}