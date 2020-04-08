<?php

namespace App\Actions\Home;

use App\Actions\Interfaces\Home\GetMoreTricksInterface;
use App\Entity\Figure;
use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @Route("/more_tricks", name="more.tricks")
 */
class GetMoreTricks
{
    /** @var Environment  */
    private $environment;
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var FigureRepository */
    private $tricksRepo;

    /**
     * GetMoreTricks constructor.
     * @param Environment $environment
     * @param EntityManagerInterface $manager
     * @param FigureRepository $tricksRepo
     */
    public function __construct(
        Environment $environment,
        EntityManagerInterface $manager,
        FigureRepository $tricksRepo
    ) {
        $this->environment = $environment;
        $this->manager = $manager;
        $this->tricksRepo = $tricksRepo;
    }


    /**
     * @param Request $request
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(Request $request)
    {
        $pageId = $request->query->get('page');
        $tricksCount = $this->tricksRepo->count([]);

        /** @var Figure $tricksToDisplay */
        $tricksToDisplay = $this->tricksRepo->findBy(
            [],
            [],
            Figure::LIMIT_PER_PAGE,
            $this->computeOffset($pageId)
        );

        return new Response(
            $this->environment->render(
                'block_for_include/block_for_tricks_ajax.html.twig',
                [
                    'tricksToDisplay' => $tricksToDisplay,
                    'has-tricks-to-display' => $this->hasTricksToDisplay($pageId, $tricksCount),
                ]
            )
        );
    }

    /**
     * @param $pageId
     * @return float|int
     */
    public function computeOffset($pageId)
    {
        return $pageId * Figure::LIMIT_PER_PAGE - Figure::LIMIT_PER_PAGE;
    }

    /**
     * @param $pageId
     * @param int $tricksCount
     * @return bool
     */
    public function hasTricksToDisplay($pageId, int $tricksCount): bool
    {
        return $pageId * Figure::LIMIT_PER_PAGE < $tricksCount;
    }
}
