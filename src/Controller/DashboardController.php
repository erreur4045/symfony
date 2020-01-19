<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\User;
use App\Form\ProfilePictureType;
use App\Repository\FigureRepository;
use App\Repository\UserRepository;
use App\Services\FormResolverUploadPicture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
//todo : sortir de l'abstract controller
class DashboardController extends AbstractController
{
    /** @var FigureRepository */
    private $figure;

    /** @var UserRepository */
    private $user;

    /** @var Environment */
    private $environment;

    /** @var EntityManagerInterface */
    private $manager;

    /** @var Filesystem */
    private $filesystem;

    /** @var FlashBagInterface */
    private $bag;
    /**
     * @var FormResolverUploadPicture
     */
    private $formResolverUploadPicture;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct
    (
        FigureRepository $figure,
        UserRepository $user,
        Environment $environment,
        TokenStorageInterface $tokenStorage,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $manager,
        Filesystem $filesystem,
        FlashBagInterface $bag,
        FormResolverUploadPicture $formResolverUploadPicture
    ) {
        $this->figure = $figure;
        $this->user = $user;
        $this->environment = $environment;
        $this->tokenStorage = $tokenStorage;
        $this->formFactory = $formFactory;
        $this->manager = $manager;
        $this->filesystem = $filesystem;
        $this->bag = $bag;
        $this->formResolverUploadPicture = $formResolverUploadPicture;
    }

    /**
     * @Route("/dashboard", name="app_dashboard")
     */

    public function index(UserInterface $user = null, ObjectManager $manager, Request $request, ObjectManager $managerORM)
    {
        if ($user == null){
            return new Response($this->environment->render('block_for_include/no_connect.html.twig', [
            ]));
        }
        /** @var Figure $figures */
        $figures = $this->figure->findBy(['user' => $user->getId()]);
        /** @var User $user_data */
        $user_data = $this->tokenStorage->getToken()->getUser();

        if ($user->getName() == $this->tokenStorage->getToken()->getUser()) {
            /** @var User $userdata */
            $userdata = $this->tokenStorage->getToken()->getUser();
            $type = ProfilePictureType::class;
            $form = $this->formResolverUploadPicture->getForm($request, $type);
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['profilePicture']->getData();
            if ($form->isSubmitted() && $form->isValid()) {
                $this->formResolverUploadPicture->treatment($form, $userdata);
                $this->bag->add('success', 'Votre avatar a été modifié');
                return $this->redirect($this->generateUrl('app_dashboard'));
            }
            return new Response($this->environment->render('dashboard/index.html.twig', [
                'form' => $form->createView(),
                'user' => $user,
                'controller_name' => 'Mon Dashboard',
                'title' => 'Mon Dashboard',
                'figure' => $figures,
                'image' => $user_data->getProfilePicture()
            ]));
        }
        return new RedirectResponse($this->generateUrl('home'));
    }
}
