<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : AddMediaController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Medias;

use App\Entity\Figure;
use App\Form\FigureAddMediaType;
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
 * @Route("/edit/medias/{slug}", name="add.medias")
 * @IsGranted("ROLE_USER")
 */
class AddMedias
{
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var FlashBagInterface  */
    private $bag;
    /** @var Environment  */
    private $environment;
    /** @var UrlGeneratorInterface  */
    private $router;
    /** @var FormResolverMedias  */
    private $formResolverMedias;

    /**
     * AddMedias constructor.
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param Environment $environment
     * @param UrlGeneratorInterface $router
     * @param FormResolverMedias $formResolverMedias
     */
    public function __construct(
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        Environment $environment,
        UrlGeneratorInterface $router,
        FormResolverMedias $formResolverMedias
    ) {
        $this->manager = $manager;
        $this->bag = $bag;
        $this->environment = $environment;
        $this->router = $router;
        $this->formResolverMedias = $formResolverMedias;
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
        /** @var Figure $figure */
        $figure = $this->manager->getRepository(Figure::class)->findOneBy(
            ['slug' => $request->attributes->get('slug')]
        );
        $form = $this->formResolverMedias->getForm($request, FigureAddMediaType::class);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->formResolverMedias->updateMedias($form, $figure);
            $this->bag->add('success', 'Les medias ont été ajouter');
            return new RedirectResponse($this->router->generate('trick', ['slug' => $figure->getSlug()]));
        }
        return new Response($this->environment->render('media/UpdateMedias.html.twig', [
                    'form' => $form->createView(),
                    'title' => 'Changer une image'
                ]));
    }
}
