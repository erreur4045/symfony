<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : DeleteTrickController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Trick;

use App\Actions\OwnAbstractController;
use App\Entity\Figure;
use App\Entity\Pictureslink;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DeleteTrickController extends OwnAbstractController
{
    /**
     * @Route("/delete/{slug}", name="delete.trick")
     * @IsGranted("ROLE_USER")
     */
    public function deleteTrick(Figure $figure)
    {
            /** @var Pictureslink $image */
            $image = $this->manager->getRepository(Pictureslink::class)->findBy(['figure' => $figure->getId()]);
        foreach ($image as $images) {
            $this->filesystem->remove([
                    '',
                    '',
                    $this->tricksPicturesDirectory . $images->getLinkpictures()
                ]);
        }
            $this->manager->remove($figure);
        $this->manager->flush();
        $this->bag->add('success', 'La figure a été supprimé');
        return new RedirectResponse($this->router->generate('home'));
    }
}
