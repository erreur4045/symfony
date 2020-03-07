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

use App\Entity\Figure;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @Route("/", name="home")
 */
class GetHomePage
{
    /** @var Environment  */
    private $environment;
    /** @var EntityManagerInterface  */
    private $manager;

    /**
     * GetHomePage constructor.
     * @param Environment $environment
     * @param EntityManagerInterface $manager
     */
    public function __construct(Environment $environment, EntityManagerInterface $manager)
    {
        $this->environment = $environment;
        $this->manager = $manager;
    }


    public function __invoke()
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
