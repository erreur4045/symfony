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
use App\Repository\FigureRepository;
use App\Services\FormResolvers\FormResolverMedias;
use App\Traits\RequestTools;
use App\Traits\ViewsTools;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @Route("/edit/medias/{slug}", name="add.medias")
 * @IsGranted("ROLE_USER")
 */
class AddMedias
{
    use ViewsTools, RequestTools;

    const MEDIA_UPDATE_TWIG = 'media/UpdateMedias.html.twig';
    const FORM = FigureAddMediaType::class;

    /** @var Environment  */
    private $environment;
    /** @var UrlGeneratorInterface  */
    private $router;
    /** @var FormResolverMedias  */
    private $formResolverMedias;
    /** @var FigureRepository */
    private $figureRepo;

    /**
     * AddMedias constructor.
     * @param Environment $environment
     * @param UrlGeneratorInterface $router
     * @param FormResolverMedias $formResolverMedias
     * @param FigureRepository $figureRepo
     */
    public function __construct(
        Environment $environment,
        UrlGeneratorInterface $router,
        FormResolverMedias $formResolverMedias,
        FigureRepository $figureRepo
    ) {
        $this->environment = $environment;
        $this->router = $router;
        $this->formResolverMedias = $formResolverMedias;
        $this->figureRepo = $figureRepo;
    }


    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(Request $request)
    {
        $figureSlug = $this->getSlugFrom($request);

        /** @var Figure $figure */
        $figure = $this->figureRepo->getTrickFromSlug($figureSlug);

        $form = $this->formResolverMedias->getForm($request, self::FORM);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->formResolverMedias->updateMedias($form, $figure);
            $this->displayMessage('success', 'Les medias ont a été ajoutés');
            $context = ['slug' => $figureSlug];
            return new RedirectResponse($this->router->generate('trick', $context));
        }

        $contextView = ['form' => $form->createView(),];
        return new Response($this->environment->render(
            self::MEDIA_UPDATE_TWIG,
            $contextView
        )
        );
    }
}
