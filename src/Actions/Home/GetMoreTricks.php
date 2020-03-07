<?php

namespace App\Actions\Home;

use App\Entity\Figure;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * @Route("/more_tricks", name="more.tricks")
 */
class GetMoreTricks
{
    /** @var Environment  */
    private $environment;
    /** @var EntityManagerInterface  */
    private $manager;

    /**
     * GetMoreTricks constructor.
     * @param Environment $environment
     * @param EntityManagerInterface $manager
     */
    public function __construct(Environment $environment, EntityManagerInterface $manager)
    {
        $this->environment = $environment;
        $this->manager = $manager;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(Request $request)
    {
        $pageId = $request->query->get('page');
        $offset = $pageId * Figure::LIMIT_PER_PAGE - Figure::LIMIT_PER_PAGE ;
        $nb_tricks = $this->manager->getRepository(Figure::class)->count([]);
        $rest = $pageId * Figure::LIMIT_PER_PAGE < $nb_tricks ? true : false;

        /** @var Figure $tricksToShow */
        $tricksToShow = $this->manager->getRepository(Figure::class)->findBy([], [], Figure::LIMIT_PER_PAGE, $offset);

        return new Response(
            $this->environment->render(
                'block_for_include/block_for_tricks_ajax.html.twig',
                [
                'tricksToShow' => $tricksToShow,
                'rest' => $rest
                ]
            )
        );
    }
}
