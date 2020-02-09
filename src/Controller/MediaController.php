<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Entity\User;
use App\Entity\Videolink;
use App\Form\AddSinglePictureType;
use App\Form\FigureType;
use App\Form\VideolinkType;
use App\Repository\FigureRepository;
use App\Services\FormResolverMedias;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

class MediaController
{
    /**
     *
     *
     * @var TricksController *
     */
    private $trick;

    /**
     *
     *
     * @var TokenStorageInterface *
     */
    private $tokenStorage;

    /**
     *
     *
     * @var Environment *
     */
    private $templating;

    /**
     *
     *
     * @var FigureType *
     */
    private $figureType;

    /**
     *
     *
     * @var FormFactory *
     */
    private $formFactory;

    /**
     *
     *
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     *
     *
     * @var FlashBagInterface
     */
    private $bag;

    /**
     *
     *
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     *
     *
     * @var Filesystem
     */
    private $filesystem;

    /**
     *
     *
     * @var Environment
     */
    private $environment;

    /**
     *
     *
     * @var string
     */
    private $tricksPicturesDirectory;

    /**
     *
     *
     * @var FormResolverMedias
     */
    private $formResolverMedias;

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
        Environment $environment,
        string $tricksPicturesDirectory,
        FormResolverMedias $formResolverMedias
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
        $this->tricksPicturesDirectory = $tricksPicturesDirectory;
        $this->formResolverMedias = $formResolverMedias;
    }

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
                $NewFirstImages = $this->manager->getRepository(Pictureslink::class)->findBy(
                    [
                    'figure' => $image[0]->getFigure()->getId(),
                    'first_image' => false
                    ]
                );
                $NewFirstImages[0]->setFirstImage(1);
                $this->manager->persist($NewFirstImages[0]);
                $this->manager->flush();
            } else {
                $this->manager->remove($image[0]);
                $this->manager->flush();
            }
            $this->filesystem->remove(
                [
                '',
                '',
                $this->tricksPicturesDirectory . $image[0]->getLinkpictures()
                ]
            );
            $this->bag->add('success', 'La figure a été mise a jour');
            return new RedirectResponse(
                $this->router->generate(
                    'trick',
                    ['slug' => $image[0]->getFigure()->getSlug()]
                )
            );
        }
        return new RedirectResponse($this->router->generate('home'));
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
            return new RedirectResponse(
                $this->router->generate(
                    'trick',
                    ['slug' => $video[0]->getFigure()->getSlug()]
                )
            );
        } else {
            return new Response(
                $this->environment->render(
                    'block_for_include/no_connect.html.twig',
                    [
                    ]
                )
            );
        }
    }

    /**
     * @Route("/media/update/picture/{id}", name="update.picture")
     */
    public function updatePicture($id, Request $request)
    {
        if ($this->tokenStorage->getToken()->getUser() == "anon.") {
            return new Response(
                $this->environment->render(
                    'block_for_include/no_connect.html.twig',
                    [
                    ]
                )
            );
        }
        /** @var Pictureslink $exPicture */
        $exPicture = $this->manager->getRepository(Pictureslink::class)->find($id);
        /** @var Figure $figure */
        $figure = $this->manager->getRepository(Figure::class)
            ->findOneBy(['id' => $exPicture->getFigure()->getId()]);
        if ($this->tokenStorage->getToken()->getUser() != "anon.") {
            /** @var User $userdata */
            $user = $this->tokenStorage->getToken()->getUser();
            $form = $this->formResolverMedias->getForm($request, AddSinglePictureType::class);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->formResolverMedias->updatePictureTrick($form, $user, $figure, $exPicture);
                $this->bag->add('success', 'La photo a été modifié');
                return new RedirectResponse($this->router->generate('trick', ['slug' => $figure->getSlug()]));
            }
            return new Response(
                $this->environment->render(
                    'media/UpdatePicture.html.twig',
                    [
                    'form' => $form->createView(),
                    'title' => 'Changer une image'
                    ]
                )
            );
        }
        return new RedirectResponse($this->router->generate('home'));
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
            return new Response(
                $this->environment->render(
                    'block_for_include/no_connect.html.twig',
                    [
                    ]
                )
            );
        }

        $form = $this->formResolverMedias->getForm($request, VideolinkType::class);
        if ($this->tokenStorage->getToken()->getUser() != "anon.") {
            if ($form->isSubmitted() && $form->isValid()) {
                $this->formResolverMedias->updateVideoLink($form, $figure, $exVideo);
                $this->bag->add('success', 'La video a été modifié');
                return new RedirectResponse($this->router->generate('trick', ['slug' => $figure->getSlug()]));
            }
        }

        return new Response(
            $this->environment->render(
                'media/UpdateVideo.html.twig',
                [
                'form' => $form->createView(),
                'title' => 'Changer une image'
                ]
            )
        );
    }
}
