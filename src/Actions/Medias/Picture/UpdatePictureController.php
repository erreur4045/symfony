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


use App\Actions\OwnAbstractController;
use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Entity\User;
use App\Form\AddSinglePictureType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdatePictureController extends OwnAbstractController
{
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
}