<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Tests\Compiler\F;
use Symfony\Component\Routing\Annotation\Route;
use Faker;

class HomeController extends AbstractController
{

    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        $figure = $this->getDoctrine()->getRepository(Figure::class)->find(142);
        $image = $this->getDoctrine()->getRepository(Pictureslink::class)->find(142);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'figure' => $figure,
            'image' => $image
        ]);
    }

    /**
     * @Route("/user/{id}", name="userfind")
     */
    public function getUserData($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        //$datauser = $user->findOneBy(['id' => $id]);
    dump($user);
        return $this->render('home/index.html.twig', [
           'datauserview' => $user,
        ]);
    }


}
