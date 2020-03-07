<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : UpdatePictureController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Medias\Picture;

use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Entity\User;
use App\Form\AddSinglePictureType;
use App\Services\FormResolvers\FormResolverMedias;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

/**
 * @Route("/media/update/picture/{id}", name="update.picture")
 * @IsGranted("ROLE_USER")
 */
class UpdatePicture
{
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var TokenStorageInterface  */
    private $tokenStorage;
    /** @var FormResolverMedias  */
    private $formResolverMedias;
    /** @var FlashBagInterface  */
    private $bag;
    /** @var UrlGeneratorInterface  */
    private $router;
    /** @var Environment  */
    private $environment;

    /**
     * UpdatePicture constructor.
     * @param EntityManagerInterface $manager
     * @param TokenStorageInterface $tokenStorage
     * @param FormResolverMedias $formResolverMedias
     * @param FlashBagInterface $bag
     * @param UrlGeneratorInterface $router
     * @param Environment $environment
     */
    public function __construct(
        EntityManagerInterface $manager,
        TokenStorageInterface $tokenStorage,
        FormResolverMedias $formResolverMedias,
        FlashBagInterface $bag,
        UrlGeneratorInterface $router,
        Environment $environment
    ) {
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
        $this->formResolverMedias = $formResolverMedias;
        $this->bag = $bag;
        $this->router = $router;
        $this->environment = $environment;
    }

    public function __invoke($id, Request $request)
    {
        /** @var Pictureslink $exPicture */
        $exPicture = $this->manager->getRepository(Pictureslink::class)->find($id);
        /** @var Figure $figure */
        $figure = $this->manager->getRepository(Figure::class)
            ->findOneBy(['id' => $exPicture->getFigure()->getId()]);
        /** @var User $userdata */
            $user = $this->tokenStorage->getToken()->getUser();
        $form = $this->formResolverMedias->getForm($request, AddSinglePictureType::class);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->formResolverMedias->updatePictureTrick($form, $figure, $exPicture);
            $this->bag->add('success', 'La photo a été modifié');
            return new RedirectResponse($this->router->generate('trick', ['slug' => $figure->getSlug()]));
        }
            return new Response($this->environment->render('media/UpdatePicture.html.twig', [
                        'form' => $form->createView(),
                        'title' => 'Changer une image'
                    ]));
    }
}
