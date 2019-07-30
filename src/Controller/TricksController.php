<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Entity\User;
use App\Form\FigureType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;

class TricksController extends AbstractController
{
    /**
     * @Route("/tricks", name="tricks")
     */
    public function index()
    {
        return $this->render('tricks/index.html.twig', [
            'controller_name' => 'TricksController',
        ]);
    }

    /**
     * @Route("/savetricks", name="savetricks2")
     */
    public function saveTricks(ObjectManager $manager , Request $request)
    {
        $figure = new Figure();
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($figure);
            $manager->flush();

            return $this->redirectToRoute('savetricks');
        }
        return $this->render('tricks/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/trick/{id}", name="trick")
     */
    public function getTrick($id)
    {
        $image = $this->getDoctrine()->getRepository(Pictureslink::class)->find($id);
        $datatricks = $this->getDoctrine()->getRepository(Figure::class)->find($id);
        $comments = $this->getDoctrine()->getRepository(Comments::class)->findBy(['idfigure' => $id ]);
        dump($comments);
        return $this->render('tricks/trick.html.twig', [
            'image' => $image,
            'data' => $datatricks,
            'comment' => $comments
        ]);
    }
}
