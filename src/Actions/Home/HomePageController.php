<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : HomePageController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Home;

use App\Actions\OwnAbstractController;
use App\Entity\Figure;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends OwnAbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        /** @var Figure $figures */
        $figures = $this->manager->getRepository(Figure::class)
            ->findBy([], [], Figure::LIMIT_PER_PAGE, null);
        /** @var $nbPageMax */
        $nbPageMax = ceil($this->manager->getRepository(Figure::class)
                ->count([]) / Figure::LIMIT_PER_PAGE);
        $rest = $nbPageMax > 1 ? true : false;
        return new Response($this->environment->render('home/index.html.twig', [
                    'figures' => $figures,
                    'title' => 'SnowTricks',
                    'pagemax' => $nbPageMax,
                    'rest' => $rest
                ]));
    }
}
