<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Article;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index()
    {
        var_dump(phpinfo());
        print_r(get_loaded_extensions());
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
