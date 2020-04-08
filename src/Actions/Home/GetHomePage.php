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

use App\Actions\Interfaces\Home\GetHomePageInterface;
use App\Entity\Figure;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @Route("/", name="home")
 */
class GetHomePage
{
    /** @var Environment  */
    private $environment;
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var FigureRepository */
    private $tricksRepo;

    /**
     * GetHomePage constructor.
     * @param Environment $environment
     * @param EntityManagerInterface $manager
     * @param FigureRepository $tricksRepo
     */
    public function __construct(
        Environment $environment,
        EntityManagerInterface $manager,
        FigureRepository $tricksRepo
    )
    {
        $this->environment = $environment;
        $this->manager = $manager;
        $this->tricksRepo = $tricksRepo;
    }


    /**
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke()
    {
        /** @var Figure $figures */
        $figures = $this->tricksRepo->findBy([], [], Figure::LIMIT_PER_PAGE, null);
        $tricksCount = $this->tricksRepo->count([]);
        $lastPage = ceil($tricksCount / Figure::LIMIT_PER_PAGE);
        $hasTricksToDisplay = $lastPage > 1;
        return new Response(
            $this->environment->render(
                'home/index.html.twig', [
                    'figures' => $figures,
                    'title' => 'SnowTricks',
                    'last-page' => $lastPage,
                    'has-tricks-to-display' => $hasTricksToDisplay
                ]));
    }
}
