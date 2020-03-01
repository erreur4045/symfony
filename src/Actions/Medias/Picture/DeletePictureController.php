<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : DeletePicture.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Medias\Picture;


use App\Actions\OwnAbstractController;
use App\Entity\Pictureslink;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DeletePictureController extends OwnAbstractController
{
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
}