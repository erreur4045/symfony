<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : EditTrickController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Trick;

use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Entity\Videolink;
use App\Form\FigureEditType;
use App\Services\FormResolvers\FormResolverTricks;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * @Route("/edit/{slug}", name="edit.trick")
 * @IsGranted("ROLE_USER")
 */
class EditTrick
{
    /** @var Environment  */
    private $templating;
    /** @var UrlGeneratorInterface  */
    private $router;
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var FormResolverTricks */
    private $formResolverTricks;

    /**
     * EditTrick constructor.
     * @param Environment $templating
     * @param UrlGeneratorInterface $router
     * @param EntityManagerInterface $manager
     * @param FormResolverTricks $formResolverTricks
     */
    public function __construct(
        Environment $templating,
        UrlGeneratorInterface $router,
        EntityManagerInterface $manager,
        FormResolverTricks $formResolverTricks
    ) {
        $this->templating = $templating;
        $this->router = $router;
        $this->manager = $manager;
        $this->formResolverTricks = $formResolverTricks;
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(Request $request)
    {
        /** @var Figure $datatricks */
        $figure = $this->manager->getRepository(Figure::class)
            ->findOneBy(['slug' => $request->attributes->get('slug')]);
        if (is_null($figure)) {
            throw new NotFoundHttpException('La figure n\'existe pas');
        }

        /** @var Videolink $video */
        $video = $this->manager->getRepository(Videolink::class)->findBy(['figure' => $figure->getId()]);
        $hasOthermedia = empty($this->manager->getRepository(Pictureslink::class)
            ->findBy(['figure' => $figure->getId(), 'first_image' => 0])) && empty($video) ? true : false;
        $hasOtherPicture = empty($this->manager->getRepository(Pictureslink::class)
            ->findBy(['figure' => $figure->getId(), 'first_image' => 0])) ? true : false;
        /** @var Form $form */
        $form = $this->formResolverTricks->getForm($request, FigureEditType::class, $figure);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->formResolverTricks->updateTrick($figure);
            return new RedirectResponse($this->router->generate('trick', ['slug' => $figure->getSlug()]));
        }
        return new Response($this->templating->render('tricks/edittrick.html.twig', [
                    'figure' => $figure,
                    'form' => $form->createView(),
                    'h1' => 'Modification de la figure',
                    'emptyMedia' => $hasOthermedia,
                    'otherPicture' => $hasOtherPicture
                ]));
    }
}
