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
use App\Repository\FigureRepository;
use App\Responder\Interfaces\ResponderInterface;
use App\Traits\HomeTools;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @Route("/", name="home")
 */
class GetHomePage
{
    use HomeTools;

    const HOME_TWIG = 'home/index.html.twig';

    private Environment $environment;
    private EntityManagerInterface $manager;
    private FigureRepository $tricksRepo;
    private ResponderInterface $responder;

    /**
     * GetHomePage constructor.
     * @param Environment $environment
     * @param EntityManagerInterface $manager
     * @param FigureRepository $tricksRepo
     * @param ResponderInterface $responder
     */
    public function __construct(
        Environment $environment,
        EntityManagerInterface $manager,
        FigureRepository $tricksRepo,
        ResponderInterface $responder
    ) {
        $this->environment = $environment;
        $this->manager = $manager;
        $this->tricksRepo = $tricksRepo;
        $this->responder = $responder;
    }


    /**
     * @return Response
     */
    public function __invoke()
    {
        /** @var Figure $figures */
        $figures = $this->tricksRepo->getFirstPageOfTricks();
        $countTricks = $this->tricksRepo->count([]);
        $lastPage = ceil($countTricks / Figure::LIMIT_PER_PAGE);
        $contextView = [
            'figures' => $figures,
            'title' => 'SnowTricks',
            'lastPage' => $lastPage,
            'hasTricksToDisplay' => $lastPage > 1,
        ];
         return $this->responder->render(
             self::HOME_TWIG,
             $contextView
         );
    }
}
