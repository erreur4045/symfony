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

    public function __construct(
        EntityManagerInterface $manager,
        Environment $environment)
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

        /** @var $nbPageMax */
        $nbPageMax = ceil($this->manager->getRepository(Figure::class)->count([]) / Figure::LIMIT_PER_PAGE);

        /** @var int $nb_tricks */
        $nb_tricks = $this->manager->getRepository(Figure::class)->count([]);

        return new Response($this->environment->render('home/index.html.twig', [
            'figures' => $figures,
            'title' => 'SnowTricks',
            'pagemax' => $nbPageMax,
            'rest' => Figure::LIMIT_PER_PAGE < $nb_tricks
        ]));
    }

    /**
     * @Route("/more_tricks", name="more.tricks")
     */
    public function loadTricks(Request $request)
    {
        $pageId = $request->query->get('page');
        $offset = $pageId * Figure::LIMIT_PER_PAGE - Figure::LIMIT_PER_PAGE ;
        $nb_tricks = $this->manager->getRepository(Figure::class)->count([]);
        if ($nb_tricks > Figure::LIMIT_PER_PAGE){
            $rest = false;
        }
        else{
            $rest = $pageId * Figure::LIMIT_PER_PAGE < $nb_tricks;
        }

        /** @var Figure $tricksToShow */
        $tricksToShow = $this->manager->getRepository(Figure::class)->findBy([], [], Figure::LIMIT_PER_PAGE, $offset);

        return new Response($this->environment->render('block_for_include/block_for_tricks_ajax.html.twig',[
                'tricksToShow' => $tricksToShow,
                'rest' => $rest
            ]));
    }
}
