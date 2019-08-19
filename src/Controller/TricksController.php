<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Form\FigureType;
use App\Repository\FigureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;


class TricksController
{
    /** @var TricksController **/
    private $trick;

    /** @var Environment **/
    private $templating;

    /** @var FigureType **/
    private $figureType;

    /** @var FormFactory **/
    private $formFactory;

    /** @var  UrlGeneratorInterface */
    private $router;

    /** @var FlashBagInterface */
    private $bag;


    public function __construct(
        FigureRepository $trick,
        Environment $templating,
        FigureType $figureType,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $router,
        FlashBagInterface $bag
    )
    {
        $this->trick = $trick;
        $this->templating = $templating;
        $this->figureType = $figureType;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->bag = $bag;
    }

    /**
     * @Route("/tricks", name="tricks")
     *
     */
    public function index()
    {
        $tricks = $this->trick->findBy(array(), ['id' => 'DESC'], $limit = 25);
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
        $form = $this->formFactory->create(FigureType::class, $figure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($figure);
            $manager->flush();
            return new RedirectResponse($this->router->generate('tricks'));
        }
        return new Response($this->templating->render('tricks/edittrick.html.twig', [
            'figure' => $figure,
            'form' => $form->createView()
        ]));
    }

    /**
     * @Route("/edit/{id}", name="edit.trick")
     */
    public function editTrick(Figure $figure, ObjectManager $manager, Request $request)
    {
        $form = $this->formFactory->create(FigureType::class, $figure);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($figure);
            $manager->flush();
            $this->bag->add('success', 'Votre figure a été mise a jour');
            return new RedirectResponse($this->router->generate('tricks'));
        }
        return new Response($this->templating->render('tricks/edittrick.html.twig', [
            'figure' => $figure,
            'form' => $form->createView()
        ]));
    }

    /**
     * @Route("/delete/{id}", name="delete.trick")
     */
    public function deleteTrick(Figure $figure, ObjectManager $manager)
    {
        //todo : boucle pour supp video et image associer
        $manager->remove($figure);
        $manager->flush();
        $this->bag->add('success', 'Votre figure a été supprimé');
        return new RedirectResponse($this->router->generate('tricks'));

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
        return $this->templating->render('tricks/trick.html.twig', [
            'image' => $image,
            'data' => $datatricks,
            'comment' => $comments
        ]);
    }

}
