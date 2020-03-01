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

use App\Actions\OwnAbstractController;
use App\Entity\Figure;
use App\Entity\Videolink;
use App\Form\VideolinkType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateVideoController extends OwnAbstractController
{
    /**
     * @Route("/media/update/video/{id}", name="update.video")
     * @IsGranted("ROLE_USER")
     */
    public function updateVideo(Request $request)
    {
        /** @var Videolink $exPicture */
        $exVideo = $this->manager->getRepository(Videolink::class)->find($request->query->getInt('id'));
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
