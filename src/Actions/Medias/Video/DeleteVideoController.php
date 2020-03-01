<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : DeleteVideo.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Medias\Video;


use App\Actions\OwnAbstractController;
use App\Entity\Videolink;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteVideoController extends OwnAbstractController
{
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
}