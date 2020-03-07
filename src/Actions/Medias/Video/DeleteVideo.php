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

use App\Actions\Interfaces\Medias\Video\DeleteVideoInterface;
use App\Entity\Videolink;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/media/delete/video/{id}", name="delete.video")
 * @IsGranted("ROLE_USER")
 */
class DeleteVideo implements DeleteVideoInterface
{
    /** @var UrlGeneratorInterface  */
    private $router;
    /** @var FlashBagInterface  */
    private $bag;
    /** @var EntityManagerInterface  */
    private $manager;

    /**
     * DeleteVideo constructor.
     * @param UrlGeneratorInterface $router
     * @param FlashBagInterface $bag
     * @param EntityManagerInterface $manager
     */
    public function __construct(UrlGeneratorInterface $router, FlashBagInterface $bag, EntityManagerInterface $manager)
    {
        $this->router = $router;
        $this->bag = $bag;
        $this->manager = $manager;
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function __invoke(Request $request)
    {
        $video = $this->manager->getRepository(Videolink::class)->findBy(['id' => $request->attributes->getInt('id')]);
        $this->manager->remove($video[0]);
        $this->manager->flush();
        $this->bag->add('success', 'La figure a été mise a jour');
        return new RedirectResponse(
            $this->router->generate(
                'trick',
                ['slug' => $video[0]->getFigure()->getSlug()]
            )
        );
    }
}
