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
        //todo : c'est salement sale  !
        //todo : $figure ne contient pas les collections photos/videos
        $user = $this->getUser();
        dump($user);
        //recuperer les photos de la figures ?
        /** @var Figure $figures */
        $figures = $this->manager->getRepository(Figure::class)->findBy([], ['id' => 'DESC'], $limit = 10);
        /** @var Figure $figure_picture */
        foreach ($figures as $figure_picture){
            /** @var Pictureslink $image */
            $image[] = $this->manager->getRepository(Pictureslink::class)->findBy(['figure' => $figure_picture->getId()]);
        }
        return new Response($this->environment->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'figures' => $figures,
            'images' => $image
        ]));
    }

}
