<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Form\CommentType;
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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class TricksController
 * @package App\Controller
 */
class TricksController
{
    /** @var TricksController **/
    private $trick;

    /** @var TokenStorageInterface **/
    private $tokenStorage;

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


    /**
     * TricksController constructor.
     * @param FigureRepository $trick
     * @param Environment $templating
     * @param FigureType $figureType
     * @param FormFactoryInterface $formFactory
     * @param UrlGeneratorInterface $router
     * @param FlashBagInterface $bag
     * @param EntityManagerInterface $manager
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        FigureRepository $trick,
        Environment $templating,
        FigureType $figureType,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $router,
        FlashBagInterface $bag,
        EntityManagerInterface $manager,
        TokenStorageInterface $tokenStorage
    )
    {
        $this->trick = $trick;
        $this->templating = $templating;
        $this->figureType = $figureType;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->bag = $bag;
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
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
        $user = $this->tokenStorage->getToken()->getUser();
        $form = $this->formFactory->create(FigureType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $figure = $form->getData();
            $figure->setUser($user);
            $manager->persist($figure);
            $manager->flush();
            return new RedirectResponse($this->router->generate('tricks'));
        }
        return new Response($this->templating->render('tricks/newtrick.html.twig', [
            'form' => $form->createView()
        ]));
    }



    /**
     * @Route("/delete/{id}", name="delete.trick")
     * @param Figure $figure
     * @param ObjectManager $manager
     * @return RedirectResponse
     */
    public function deleteTrick(Figure $figure, ObjectManager $manager)
    {
        //todo : PRIORITY 2 -> boucle pour supp video et image associer
       if ($figure->getUser()->getName() == $this->tokenStorage->getToken()->getUser()) {
           $manager->remove($figure);
           $manager->flush();
           $this->bag->add('success', 'Votre figure a été supprimé');
           return new RedirectResponse($this->router->generate('tricks'));
       }
       else $this->bag->add('warning', 'Vous ne pouvez pas supprimer cette figure');
        return new RedirectResponse($this->router->generate('tricks'));
    }

    /**
     * @Route("/trick/{id}", name="trick")
     * @param Request $request
     * @param ObjectManager $manager
     * @param Figure $figure
     * @return RedirectResponse|Response
     */
    public function getTrick(Request $request, ObjectManager $manager, Figure $figure)
    {

        $datatricks = $this->manager->getRepository(Figure::class)->find($request->attributes->get('id'));
        if(is_null($datatricks)) {
          throw new ExceptionContr('Cette figure n\'existe pas');
          // todo : https://symfony.com/doc/current/controller/error_pages.html
        }
        $form = $this->formFactory->create(CommentType::class);
        $form->handleRequest($request);
        $user = $this->tokenStorage->getToken()->getUser();
        // todo : condition is connect
        if($form->isSubmitted() && $form->isValid()) {
            //todo : ajouter les champs datetime, user
            $comment = $form->getData();
            $comment->setDatecreate(new \DateTime());
            $comment->setIdfigure($figure);
            $comment->setUser($user);
            $manager->persist($comment);
            $manager->flush();
            $this->bag->add('success', 'Votre comemntaire a été ajouter');
            return new RedirectResponse($this->router->generate('tricks'));
        }
        $image = $this->manager->getRepository(Pictureslink::class)->findBy(['figure' => $request->attributes->get('id')]);
        $comments = $this->manager->getRepository(Comments::class)->findBy(['idfigure' => $request->attributes->get('id')]);
        return new Response($this->templating->render('tricks/trick.html.twig', [
            'form' => $form->createView(),
            'data' => $datatricks,
            'image' => $image,
            'comment' => $comments
        ]));
    }

    /**
     * @Route("/edit/{id}", name="edit.trick")
     */

    public function editTrick(Figure $figure, ObjectManager $manager, Request $request)
    {
        $datatricks = $this->manager->getRepository(Figure::class)->find($request->attributes->get('id'));
        dump($datatricks);
        if(is_null($datatricks)) {
            throw new NotFoundHttpException('Trick n\'existe pas');
            // todo : https://symfony.com/doc/current/controller/error_pages.html
        }

        if ($this->tokenStorage->getToken()->getUser() != $figure->getUser()){
            throw new NotFoundHttpException('Vous ne pouvez pas changer cette 
            figure car vous en etes pas l\'auteur');
        }

        $form = $this->formFactory->create(FigureType::class, $figure);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
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
}
