<?php

namespace App\Controller;

use App\Repository\FigureRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;

class DashboardController
{
    /** @var FigureRepository */
    private $figure;

    /** @var Environment */
    private $environment;

    public function __construct
    (
        FigureRepository $figure,
        Environment $environment
    )
    {
        $this->figure = $figure;
        $this->environment = $environment;
    }

    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function index(UserInterface $user)
    {
        $figures = $this->figure->findBy(array('user' => $user->getId()));
        return new Response($this->environment->render('dashboard/index.html.twig', [
            'controller_name' => 'Mon Dashboard',
            'title' => 'Mon Dashboard',
            'figure' => $figures
        ]));
    }


}
