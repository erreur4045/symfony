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

use App\Form\FigureAddMediaType;
use App\Repository\FigureRepository;
use App\Responder\Interfaces\ResponderInterface;
use App\Services\FormResolvers\FormResolverMedias;
use App\Traits\RequestTools;
use App\Traits\ViewsTools;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/edit/medias/{slug}", name="add.medias")
 * @IsGranted("ROLE_USER")
 */
class AddMedias
{
    use ViewsTools, RequestTools;

    const MEDIA_UPDATE_TWIG = 'media/UpdateMedias.html.twig';
    const FORM = FigureAddMediaType::class;

    private FormResolverMedias $formResolverMedias;
    private FigureRepository $figureRepo;
    private ResponderInterface $responder;

    /**
     * AddMedias constructor.
     * @param FormResolverMedias $formResolverMedias
     * @param FigureRepository $figureRepo
     * @param ResponderInterface $responder
     */
    public function __construct(
        FormResolverMedias $formResolverMedias,
        FigureRepository $figureRepo,
        ResponderInterface $responder
    ) {
        $this->formResolverMedias = $formResolverMedias;
        $this->figureRepo = $figureRepo;
        $this->responder = $responder;
    }


    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function __invoke(Request $request)
    {
        $figureSlug = $this->getSlugFrom($request);

        $figure = $this->figureRepo->getTrickFromSlug($figureSlug);

        $form = $this->formResolverMedias->getForm($request, self::FORM);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->formResolverMedias->updateMedias($form, $figure);
            $this->displayMessage('success', 'Les medias ont a été ajoutés');
            $context = ['slug' => $figureSlug];
            return $this->responder->redirect('trick', $context);
        }

        $contextView = ['form' => $form->createView(),];
        return $this->responder->render(
            self::MEDIA_UPDATE_TWIG,
            $contextView
        );
    }
}
