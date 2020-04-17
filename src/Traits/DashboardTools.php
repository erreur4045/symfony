<?php


namespace App\Traits;

use App\Actions\Dashboard\GetDashboard;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

trait DashboardTools
{
    /**
     * @return string|UserInterface
     */
    public function getConnectedUser()
    {
        return $this->tokenStorage->getToken()->getUser();
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
     * @return string
     */
    public function getProfilePicture(): string
    {
        return $this->getConnectedUser()->getProfilePicture();
    }
}
