<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\User;
use App\Form\ProfilePictureType;
use App\Repository\FigureRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
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

    public function __construct
    (
        FigureRepository $figure,
        UserRepository $user,
        Environment $environment,
        TokenStorageInterface $tokenStorage,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $manager,
        Filesystem $filesystem,
        FlashBagInterface $bag
    ) {
        $this->figure = $figure;
        $this->user = $user;
        $this->environment = $environment;
        $this->tokenStorage = $tokenStorage;
        $this->formFactory = $formFactory;
        $this->manager = $manager;
        $this->filesystem = $filesystem;
        $this->bag = $bag;
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
            $form = $this->formFactory->create(ProfilePictureType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->filesystem->remove([
                    '',
                    '',
                    $this->getParameter('picture_directory') . $userdata->getProfilePicture()
                ]);
                /** @var UploadedFile $uploadedFile */
                $uploadedFile = $form['profilePicture']->getData();
                if ($uploadedFile) {
                    $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
                        $originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
                    try {
                        $uploadedFile->move(
                            $this->getParameter('picture_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    $userdata->setProfilePicture($newFilename);
                    $managerORM->persist($userdata);
                    $managerORM->flush();
                }
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
        return new RedirectResponse($this->generateUrl('/'));
    }
}
