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
        $figures = $this->manager->getRepository(Figure::class)->findAll();

        return new Response($this->environment->render('home/index.html.twig', [
            'figures' => $figures,
            'title' => 'SnowTricks'
        ]));
    }
}
