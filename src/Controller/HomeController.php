<?php

namespace App\Controller;

use App\Entity\Figure;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class HomeController
{
    /** @var EntityManagerInterface **/
    private $manager;

    /** @var Environment **/
    private $environment;

    public function __construct(EntityManagerInterface $manager, Environment $environment)
    {
        $this->manager = $manager;
        $this->environment = $environment;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        /** @var Figure $figures */
        $figures = $this->manager->getRepository(Figure::class)->findBy([], [], Figure::LIMIT_PER_PAGE, null);
        $nbPageMax = ceil($this->manager->getRepository(Figure::class)->count([]) / Figure::LIMIT_PER_PAGE);
        return new Response($this->environment->render('home/index.html.twig', [
            'figures' => $figures,
            'title' => 'SnowTricks',
            'pagemax' => $nbPageMax
        ]));
    }

    /**
     * @Route("/more_tricks", name="more.tricks")
     */
    public function loadTricks(Request $request)
    {
        $pageId = $request->query->get('page');
        $offset = $pageId * Figure::LIMIT_PER_PAGE - 2;
        $tricksToShow = $this->manager->getRepository(Figure::class)->findBy([], [], Figure::LIMIT_PER_PAGE, $offset);
        $nb_tricks = count($this->manager->getRepository(Figure::class)->findAll());

        return new Response($this->environment->render('block_for_include/block_for_tricks_ajax.html.twig',
            ['tricksToShow' => $tricksToShow, 'rest' => $pageId * Figure::LIMIT_PER_PAGE < $nb_tricks]));
    }
}
