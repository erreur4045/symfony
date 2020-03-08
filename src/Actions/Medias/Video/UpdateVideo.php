<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : UpdateVideoController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Medias\Video;

use App\Actions\Interfaces\Medias\Video\UpdateVideoInterface;
use App\Entity\Figure;
use App\Entity\Videolink;
use App\Form\VideolinkType;
use App\Services\FormResolvers\FormResolverMedias;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * @Route("/media/update/video/{id}", name="update.video")
 * @IsGranted("ROLE_USER")
 */
class UpdateVideo implements UpdateVideoInterface
{
    /** @var FormResolverMedias  */
    private $formResolverMedias;
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var FlashBagInterface  */
    private $bag;
    /** @var Environment  */
    private $environment;
    /** @var UrlGeneratorInterface  */
    private $router;

    /**
     * UpdateVideo constructor.
     * @param FormResolverMedias $formResolverMedias
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param Environment $environment
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        FormResolverMedias $formResolverMedias,
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        Environment $environment,
        UrlGeneratorInterface $router
    ) {
        $this->formResolverMedias = $formResolverMedias;
        $this->manager = $manager;
        $this->bag = $bag;
        $this->environment = $environment;
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(Request $request)
    {
        /** @var Videolink $exVideo */
        $exVideo = $this->manager->getRepository(Videolink::class)->find($request->attributes->getInt('id'));
        /** @var Figure $figure */
        $figure = $this->manager->getRepository(Figure::class)->findOneBy(['id' => $exVideo->getFigure()->getId()]);
        $form = $this->formResolverMedias->getForm($request, VideolinkType::class);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->formResolverMedias->updateVideoLink($form, $figure, $exVideo);
            $this->bag->add('success', 'La video a été modifié');
            return new RedirectResponse($this->router->generate('trick', ['slug' => $figure->getSlug()]));
        }

        return new Response($this->environment->render('media/UpdateVideo.html.twig', [
                    'form' => $form->createView(),
                    'title' => 'Changer une image'
                ]));
    }
}
