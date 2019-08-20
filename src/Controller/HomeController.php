<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class HomeController
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
     * @Route("/home", name="home")
     */
    public function index()
    {
        $figures = $this->manager->getRepository(Figure::class)->findBy(array(), ['id' => 'DESC'], $limit = 24);

        return new Response($this->environment->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'figures' => $figures
        ]));
    }

    /**
     * @Route("/user/{id}", name="userfind")
     */
    public function getUserData($id)
    {
        $user = $this->manager->getRepository(User::class)->find($id);
        return new Response($this->environment->render('home/index.html.twig', [
           'datauserview' => $user,
        ]));
    }


}
