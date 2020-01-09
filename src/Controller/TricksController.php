<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Entity\Videolink;
use App\Form\CommentType;
use App\Form\FigureEditType;
use App\Form\FigureType;
use App\Repository\FigureRepository;
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
class TricksController extends AbstractController
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
        Filesystem $filesystem
    ) {
        $this->trick = $trick;
        $this->templating = $templating;
        $this->figureType = $figureType;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->bag = $bag;
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
        $this->filesystem = $filesystem;
    }

    /**
     * @Route("/addtrick", name="addtrick")
     */
    public function addTrick(ObjectManager $manager, Request $request)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $form = $this->formFactory->create(FigureType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Figure $figure */
            $figure = $form->getData();
            $figure->setUser($user);
            $figure->setDatecreate(new \DateTime('now'));
            foreach ($figure->getPictureslinks() as $picture) {
                /** @var UploadedFile $nameImage */
                $nameImage = $picture->getPicture();
                $originalName = $nameImage->getClientOriginalName();
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
                    $originalName);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $nameImage->guessExtension();
                try {
                    $nameImage->move(
                        $this->getParameter('figure_image'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $picture->setLinkpictures($newFilename)
                    ->setUser($user);
            }
            foreach ($figure->getVideolinks() as $video)
            {
                $videoEmbed = preg_match(
                    '/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((?:\w|-){11})(?:&list=(\S+))?$/',
                    $video->getLinkvideo(), $matches);
                $linkToStock = 'https://www.youtube.com/embed/'.$matches[1];
                $video->setLinkvideo($linkToStock);
            }
            $manager->persist($figure);
            $manager->flush();
            $this->bag->add('success', 'Votre figure a été ajouter');
            // todo : redirection sur la figure
            return new RedirectResponse($this->router->generate('trick',['slug' => $figure->getSlug()]));
            // error : Unable to generate a URL for the named route "trick/titredelafigure4" as such route does not exist.
            //return new RedirectResponse($this->router->generate('home'));
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
     * @return RedirectResponse
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
                    $this->getParameter('figure_image') . $images->getLinkpictures()
                ]);
            }
            $manager->remove($figure);
            $manager->flush();
            $this->bag->add('success', 'Votre figure a été supprimé');
            return new RedirectResponse($this->router->generate('home'));
        } else {
            $this->bag->add('warning', 'Vous ne pouvez pas supprimer cette figure');
        }
        $this->bag->add('warning', 'Vous être connecté.e.s');
        return new RedirectResponse($this->router->generate('home'));
    }

    /**
     * @Route("/trick/{slug}", name="trick")
     */
    public function getTrick(Request $request, ObjectManager $manager, PaginatorInterface $paginator)
    {
        $datatricks = $this->manager->getRepository(Figure::class)->findOneBy(['slug' => $request->attributes->get('slug')]);
        if (is_null($datatricks)) {
            throw new EntityNotFoundException('Cette figure n\'existe pas');
        }
        $form = $this->formFactory->create(CommentType::class);
        $form->handleRequest($request);
        $user = $this->tokenStorage->getToken()->getUser();
        if ($form->isSubmitted() && $form->isValid() && $user != null) {
            $comment = $form->getData();
            $comment->setDatecreate(new \DateTime())->setIdfigure($datatricks)->setUser($user);
            $manager->persist($comment);
            $manager->flush();
            $this->bag->add('success', 'Votre commentaire a été ajouter');
            return new RedirectResponse($this->router->generate('trick', ['slug' => $datatricks->getSlug()]));
        }
        $image = $this->manager->getRepository(Pictureslink::class)->findBy(['figure' => $datatricks->getId()]);
        $video = $this->manager->getRepository(Videolink::class)->findBy(['figure' => $datatricks->getId()]);
        $comments = $paginator->paginate(
            $this->manager
                ->getRepository(Comments::class)
                ->findBy(['idfigure' => $datatricks->getId()]),
            $request->query->getInt('page', 1), 10);

        return new Response($this->templating->render('tricks/trick.html.twig', [
            'form' => $form->createView(),
            'data' => $datatricks,
            'image' => $image,
            'video' => $video,
            'comment' => $comments,
            'user' => $user
        ]));
    }

    /**
     * @Route("/edit/{slug}", name="edit.trick")
     */
    public function editTrick(Figure $figure, ObjectManager $manager, Request $request)
    {
        $datatricks = $this->manager->getRepository(Figure::class)->findOneBy(['slug' => $request->attributes->get('slug')]);
        if (is_null($datatricks)) {
            throw new NotFoundHttpException('La figure n\'existe pas');
        }

        $form = $this->formFactory->create(FigureEditType::class, $datatricks);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $figure->setDateupdate(new \DateTime('now'));
            $manager->persist($figure);
            $manager->flush();
            $this->bag->add('success', 'Votre figure a été mise a jour');
            return new RedirectResponse($this->router->generate('trick', ['slug' => $datatricks->getSlug()]));
        }
        return new Response($this->templating->render('tricks/edittrick.html.twig', [
            'figure' => $figure,
            'form' => $form->createView(),
            'h1' => 'Modification de la figure'
        ]));
    }
}
