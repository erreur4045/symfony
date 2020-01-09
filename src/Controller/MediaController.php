<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Entity\User;
use App\Entity\Videolink;
use App\Form\AddSinglePictureType;
use App\Form\AddSingleVideoType;
use App\Form\FigureType;
use App\Form\VideolinkType;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;

class MediaController extends AbstractController
{
    /** @var TricksController * */
    private $trick;

    /** @var TokenStorageInterface * */
    private $tokenStorage;

    /** @var Environment * */
    private $templating;

    /** @var FigureType * */
    private $figureType;

    /** @var FormFactory * */
    private $formFactory;

    /** @var  UrlGeneratorInterface */
    private $router;

    /** @var FlashBagInterface */
    private $bag;

    /** @var EntityManagerInterface */
    private $manager;

    /** @var Filesystem */
    private $filesystem;

    /** @var Environment */
    private $environment;

    /**
     * TricksController constructor.
     */
    public function __construct(
        FigureRepository $trick,
        Environment $templating,
        FigureType $figureType,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $router,
        FlashBagInterface $bag,
        EntityManagerInterface $manager,
        TokenStorageInterface $tokenStorage,
        Filesystem $filesystem,
        Environment $environment
    ) {
        $this->trick = $trick;
        $this->templating = $templating;
        $this->figureType = $figureType;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->bag = $bag;
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
        $this->filesystem = $filesystem;
        $this->environment = $environment;
    }

    // todo : revoir les nom de route e.g. /media/{action}/{type}/{id} partout

    /**
     * @Route("/media/delete/picture/{picture}", name="delete.image")
     */
    public function deletePicture($picture)
    {
        if ($this->tokenStorage->getToken()->getUser()) {
            /** @var Pictureslink $image */
            $image = $this->manager->getRepository(Pictureslink::class)->findBy(['linkpictures' => $picture]);
            if ($image[0]->getFirstImage() == true) {
                $this->manager->remove($image[0]);
                $this->manager->flush();
                $NewFirstImages = $this->manager->getRepository(Pictureslink::class)->findBy([
                    'figure' => $image[0]->getFigure()->getId(),
                    'first_image' => false
                ]);
                $NewFirstImages[0]->setFirstImage(1);
                $this->manager->persist($NewFirstImages[0]);
                $this->manager->flush();
            } else {
                $this->manager->remove($image[0]);
                $this->manager->flush();
            }
            $this->filesystem->remove([
                '',
                '',
                $this->getParameter('figure_image') . $image[0]->getLinkpictures()
            ]);
            $this->bag->add('success', 'La figure a été mise a jour');
            return new RedirectResponse($this->router->generate('trick',
                ['slug' => $image[0]->getFigure()->getSlug()]));
        }
    }

    /**
     * @Route("/media/delete/video/{id}", name="delete.video")
     */
    public function deleteVideo($id)
    {
        if ($this->tokenStorage->getToken()->getUser()) {
            $video = $this->manager->getRepository(Videolink::class)->findBy(['id' => $id]);
            $this->manager->remove($video[0]);
            $this->manager->flush();
            $this->bag->add('success', 'La figure a été mise a jour');
            return new RedirectResponse($this->router->generate('trick',
                ['slug' => $video[0]->getFigure()->getSlug()]));
        } else {
            return new Response($this->environment->render('block_for_include/no_connect.html.twig', [
            ]));

        }
    }


    // todo : revoir les nom de route e.g. /media/{action}/{type}/{id} partout

    /**
     * @Route("/media/update/picture/{id}", name="update.picture")
     */
    public function updatePicture($id, Request $request)
    {
        if ($this->tokenStorage->getToken()->getUser() == "anon.") {
            return new Response($this->environment->render('block_for_include/no_connect.html.twig', [
            ]));
        }
        /** @var Pictureslink $exPicture */
        $exPicture = $this->manager->getRepository(Pictureslink::class)->find($id);
        /** @var Figure $figure */
        $figure = $this->manager->getRepository(Figure::class)->findOneBy(['id' => $exPicture->getFigure()->getId()]);
        if ($this->tokenStorage->getToken()->getUser() != "anon.") {
            /** @var User $userdata */
            $userdata = $this->tokenStorage->getToken()->getUser();
            $form = $this->formFactory->create(AddSinglePictureType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var Pictureslink $newPicture */
                $newPicture = $form->getData();
                $newPicture->setUser($userdata)->setFigure($figure);
                if ($exPicture->getFirstImage() == true) {
                    $newPicture->setFirstImage(true);
                }
                /** @var UploadedFile $uploadedFile */
                $uploadedFile = $form['picture']->getData();

                if ($uploadedFile) {
                    $this->filesystem->remove([
                        '',
                        '',
                        $this->getParameter('figure_image') . $exPicture
                    ]);
                    $this->manager->remove($exPicture);
                    $this->manager->flush();
                    $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
                        $originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
                    try {
                        $uploadedFile->move(
                            $this->getParameter('figure_image'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                    }
                    $newPicture->setLinkpictures($newFilename);
                    $this->manager->persist($newPicture);
                    $this->manager->flush();
                }
                $this->bag->add('success', 'La photo a été modifié');
                return new RedirectResponse($this->router->generate('trick', ['slug' => $figure->getSlug()]));
            }
            return new Response($this->environment->render('media/UpdatePicture.html.twig', [
                'form' => $form->createView(),
                'title' => 'Changer une image'
            ]));
        }
    }

    /**
     * @Route("/media/update/video/{id}", name="update.video")
     */
    public function updateVideo($id, Request $request)
    {
        /** @var Videolink $exPicture */
        $exVideo = $this->manager->getRepository(Videolink::class)->find($id);
        /** @var Figure $figure */
        $figure = $this->manager->getRepository(Figure::class)->findOneBy(['id' => $exVideo->getFigure()->getId()]);
        if ($this->tokenStorage->getToken()->getUser() == "anon.") {
            return new Response($this->environment->render('block_for_include/no_connect.html.twig', [
            ]));
        }
        $form = $this->formFactory->create(VideolinkType::class);
        if ($this->tokenStorage->getToken()->getUser() != "anon.") {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var Videolink $newVideo */
                $newVideo = $form->getData();
                $newVideoLink = $form['linkvideo']->getData();
                $videoEmbed = preg_match(
                    '/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((?:\w|-){11})(?:&list=(\S+))?$/',
                    $newVideoLink, $matches);
                $linkToStock = 'https://www.youtube.com/embed/'.$matches[1];
                $newVideo->setFigure($figure);
                $newVideo->setLinkvideo($linkToStock);
                $this->manager->remove($exVideo);
                $this->manager->persist($newVideo);
                $this->manager->flush();
            }
            $this->bag->add('success', 'La video a été modifié');
            return new RedirectResponse($this->router->generate('trick', ['slug' => $figure->getSlug()]));
        }
        return new Response($this->environment->render('media/UpdateVideo.html.twig', [
            'form' => $form->createView(),
            'title' => 'Changer une image'
        ]));
    }
}
