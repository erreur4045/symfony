<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Form\FigureType;
use App\Repository\FigureRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class TricksController
{
    /** @var TricksController **/
    private $trick;

    /** @var Environment **/
    private $templating;

    /** @var FigureType **/
    private $figureType;

    public function __construct(FigureRepository $trick, Environment $templating, FigureType $figureType)
    {
        $this->trick = $trick;
        $this->templating = $templating;
        $this->figureType = $figureType;
    }

    /**
     * @Route("/tricks", name="tricks")
     *
     */
    public function index()
    {
        $tricks = $this->trick->findBy(array(),array(),$limit = 10);
        return new Response($this->templating->render('tricks/index.html.twig', [
            'tricks' => $tricks
        ]));
    }


    /**
     * @Route("/addtrick", name="addtrick")
     */
    public function addTrick(ObjectManager $manager, Request $request)
    {
        $figure = new Figure();
        $form = $this->figureType->createForm(FigureType::class, $figure);
        $form->handleRequest($request);
        dump($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($figure);
            $manager->flush();
            $this->addFlash('success', 'Votre figure a été ajouté');

            return $this->redirectToRoute('tricks');
        }
        return $this->render('tricks/edittrick.html.twig', [
            'figure' => $figure,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit.trick")
     */
    public function editTrick(Figure $figure, ObjectManager $manager, Request $request)
    {
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($figure);
            $manager->flush();
            $this->addFlash('success', 'Votre figure a été mise a jour');

            return $this->redirectToRoute('tricks');
        }
        return $this->render('tricks/edittrick.html.twig', [
            'figure' => $figure,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete.trick")
     */
    public function deleteTrick(Figure $figure, ObjectManager $manager)
    {
        //todo : boucle pour supp video et image associer
        $manager->remove($figure);
        $manager->flush();
        $this->addFlash('success', 'Votre figure a été supprimé');

        return $this->redirectToRoute('tricks');

    }

    /**
     * @Route("/trick/{id}", name="trick")
     */
    public function getTrick($id, Figure $figure)
    {
        //todo : verrifier si l'id existe pas (demander au prof)
        $image = $this->getDoctrine()->getRepository(Pictureslink::class)->findBy(['figure' => $id]);
        $datatricks = $this->getDoctrine()->getRepository(Figure::class)->find($id);
        $comments = $this->getDoctrine()->getRepository(Comments::class)->findBy(['idfigure' => $id]);
        dump($image);
        return $this->render('tricks/trick.html.twig', [
            'image' => $image,
            'data' => $datatricks,
            'comment' => $comments
        ]);
    }

}
