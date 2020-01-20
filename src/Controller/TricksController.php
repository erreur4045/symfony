<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Entity\User;
use App\Entity\Videolink;
use App\Form\CommentType;
use App\Form\FigureEditType;
use App\Form\FigureType;
use App\Repository\FigureRepository;
use App\Services\FormResolverComment;
use App\Services\FormResolverTricks;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityNotFoundException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Core\User\UserInterface;
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
    /** @var TricksController * */
    private $trick;

    /** @var TokenStorageInterface * */
    private $tokenStorage;

    /** @var Environment * */
    private $templating;

    /** @var FigureType * */
    private $figureType;

    /** @var FormFactory * */
    private $formFactory;

    /** @var  UrlGeneratorInterface */
    private $router;

    /** @var FlashBagInterface */
    private $bag;

    /** @var EntityManagerInterface */
    private $manager;

    /** @var Filesystem */
    private $filesystem;
    /**
     * @var string
     */
    private $tricksPicturesDirectory;
    /**
     * @var FormResolverTricks
     */
    private $formResolverTricks;
    /**
     * @var FormResolverComment
     */
    private $formResolverComment;

    /**
     * TricksController constructor.
     */
    public function __construct(
        FigureRepository $trick,
        Environment $templating,
        FigureType $figureType,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $router,
        FlashBagInterface $bag,
        EntityManagerInterface $manager,
        TokenStorageInterface $tokenStorage,
        Filesystem $filesystem,
        string $tricksPicturesDirectory,
        FormResolverTricks $formResolverTricks,
        FormResolverComment $formResolverComment
    ) {
        $this->formResolverComment = $formResolverComment;
        $this->formResolverTricks = $formResolverTricks;
        $this->trick = $trick;
        $this->templating = $templating;
        $this->figureType = $figureType;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->bag = $bag;
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
        $this->filesystem = $filesystem;
        $this->tricksPicturesDirectory = $tricksPicturesDirectory;
    }

    /**
     * @Route("/addtrick", name="addtrick")
     */
    //todo au moins une image_first
    public function addTrick(Request $request)
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $form = $this->formResolverTricks->getForm($request, FigureType::class);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->formResolverTricks->addTrick($form, $user);
            return new RedirectResponse($this->router->generate('home'));
        }
        return new Response($this->templating->render('tricks/newtrick.html.twig', [
            'form' => $form->createView(),
            'h1' => 'Ajout d\'une figure'
        ]));
    }

    /**
     * @Route("/delete/{slug}", name="delete.trick")
     * @param Figure $figure
     * @param ObjectManager $manager
     * @return Response
     */
    public function deleteTrick(UserInterface $user = null, Figure $figure, ObjectManager $manager)
    {
        if($user == null){
            return new Response($this->templating->render('block_for_include/no_connect.html.twig', [
            ]));
        }
        if ($this->tokenStorage->getToken()->getUser()) {
            /** @var Pictureslink $image */
            $image = $this->manager->getRepository(Pictureslink::class)->findBy(['figure' => $figure->getId()]);
            foreach ($image as $images) {
                $this->filesystem->remove([
                    '',
                    '',
                    $this->tricksPicturesDirectory . $images->getLinkpictures()
                ]);
            }
            $manager->remove($figure);
            $manager->flush();
            $this->bag->add('success', 'La figure a été supprimé');
            return new RedirectResponse($this->router->generate('home'));
        } else {
            $this->bag->add('warning', 'Vous ne pouvez pas supprimer cette figure');
        }
        return new RedirectResponse($this->router->generate('home'));
    }

    /**
     * @Route("/trick/{slug}", name="trick")
     */
    public function getTrick(Request $request, PaginatorInterface $paginator)
    {
        /** @var Figure $figure */
        $figure = $this->manager->getRepository(Figure::class)->findOneBy(['slug' => $request->attributes->get('slug')]);
        if (is_null($figure)) {
            throw new EntityNotFoundException('Cette figure n\'existe pas');
        }
        $form = $this->formResolverComment->getForm($request, CommentType::class);
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        if ($form->isSubmitted() && $form->isValid() && $user != null) {
            $this->formResolverComment->addCom($form, $user, $figure);
            $this->bag->add('success', 'Votre commentaire a été ajouter');
            return new RedirectResponse($this->router->generate('trick', ['slug' => $figure->getSlug()]));
        }
        $image = $this->manager->getRepository(Pictureslink::class)->findBy(['figure' => $figure->getId()]);
        $video = $this->manager->getRepository(Videolink::class)->findBy(['figure' => $figure->getId()]);
        $comments = $paginator->paginate(
            $this->manager
                ->getRepository(Comments::class)
                ->findBy(['idfigure' => $figure->getId()]),
            $request->query->getInt('page', 1), 10);

        return new Response($this->templating->render('tricks/trick.html.twig', [
            'form' => $form->createView(),
            'data' => $figure,
            'image' => $image,
            'video' => $video,
            'comment' => $comments,
            'user' => $user
        ]));
    }

    /**
     * @Route("/edit/{slug}", name="edit.trick")
     */
    public function editTrick(Figure $figure, Request $request)
    {
        $datatricks = $this->manager->getRepository(Figure::class)->findOneBy(['slug' => $request->attributes->get('slug')]);
        if (is_null($datatricks)) {
            throw new NotFoundHttpException('La figure n\'existe pas');
        }
        $form = $this->formResolverTricks->getForm($request, FigureEditType::class, $datatricks);
        if ($form->isSubmitted() && $form->isValid()) {
           $this->formResolverTricks->updateTrick($figure);
            return new RedirectResponse($this->router->generate('trick', ['slug' => $datatricks->getSlug()]));
        }
        return new Response($this->templating->render('tricks/edittrick.html.twig', [
            'figure' => $figure,
            'form' => $form->createView(),
            'h1' => 'Modification de la figure'
        ]));
    }
}
