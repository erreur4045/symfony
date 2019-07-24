<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestmvcController extends AbstractController
{
    /**
     * @Route("/testmvc", name="testmvc")
     */
    public function index()
    {
        return $this->render('testmvc/index.html.twig', [
            'controller_name' => 'TestmvcController',
        ]);
    }
}
