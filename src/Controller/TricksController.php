<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Form\FigureType;
use App\Repository\FigureRepository;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Doctrine\ORM\EntityManagerInterface;

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

    /** @var EntityManagerInterface */
    private $manager;


    public function __construct(
        FigureRepository $trick,
        Environment $templating,
        FigureType $figureType,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $router,
        FlashBagInterface $bag,
        EntityManagerInterface $manager
    )
    {
        $this->trick = $trick;
        $this->templating = $templating;
        $this->figureType = $figureType;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->bag = $bag;
        $this->manager = $manager;
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
        $form = $this->formFactory->create(FigureType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $figure = $form->getData();
            $manager->persist($figure);
            $manager->flush();
            return new RedirectResponse($this->router->generate('tricks'));
        }
        return new Response($this->templating->render('tricks/edittrick.html.twig', [
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
    public function getTrick(Request $request)
    {
        $datatricks = $this->manager->getRepository(Figure::class)->find($request->attributes->get('id'));
        if(is_null($datatricks)) {
          throw new ExceptionContro('Trick n\'existe pas');
          // todo : https://symfony.com/doc/current/controller/error_pages.html
        }
        $image = $this->manager->getRepository(Pictureslink::class)->findBy(['figure' => $request->attributes->get('id')]);
        $comments = $this->manager->getRepository(Comments::class)->findBy(['idfigure' => $request->attributes->get('id')]);
        return new Response($this->templating->render('tricks/trick.html.twig', [
            'data' => $datatricks,
            'image' => $image,
            'comment' => $comments
        ]));
    }
}
