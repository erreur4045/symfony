<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HomeController extends AbstractController
{
    /** @var EntityManagerInterface */
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
        $figures = $this->manager->getRepository(Figure::class)->findBy([],[],Figure::LIMIT_PER_PAGE,null);
        $nbPageMax = ceil(count($this->manager->getRepository(Figure::class)->findAll())/Figure::LIMIT_PER_PAGE);
        dump($nbPageMax);
        return new Response($this->environment->render('home/index.html.twig', [
            'figures' => $figures,
            'title' => 'SnowTricks',
            'pagemax' => $nbPageMax
        ]));
    }

    /**
     * @Route("/more_tricks/{id}", name="more.tricks")
     */
    public function loadTricks(Request $request, $id)
    {
        $offset = $id * Figure::LIMIT_PER_PAGE - 2;
        $tricksToShow = $this->manager->getRepository(Figure::class)->findBy([],[],Figure::LIMIT_PER_PAGE, $offset);
        $nb_tricks = count($this->manager->getRepository(Figure::class)->findAll());
        //todo : ternaire
        if($id * Figure::LIMIT_PER_PAGE > $nb_tricks)
        {
            $rest = false;
        }
        else {
            $rest = true;
        }
        try {
            return new Response($this->environment->render('block_for_include/block_for_tricks_ajax.html.twig',
                ['tricksToShow' => $tricksToShow, 'rest' => $rest]
            ));
        } catch (LoaderError $e) {
        } catch (RuntimeError $e) {
        } catch (SyntaxError $e) {
        }
    }
}
