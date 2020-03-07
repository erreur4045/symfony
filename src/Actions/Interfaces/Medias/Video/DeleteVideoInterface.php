<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : DeleteVideoInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Interfaces\Medias\Video;


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
interface DeleteVideoInterface
{
    /**
     * DeleteVideo constructor.
     * @param UrlGeneratorInterface $router
     * @param FlashBagInterface $bag
     * @param EntityManagerInterface $manager
     */
    public function __construct(UrlGeneratorInterface $router, FlashBagInterface $bag, EntityManagerInterface $manager);

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function __invoke(Request $request);
}