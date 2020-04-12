<?php

namespace App\Actions\Home;

use App\Repository\FigureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @Route("/more_tricks", name="more.tricks")
 */
class GetMoreTricks
{
    use HomeTools;

    const BLOCK_FOR_TRICKS_TWIG_PATH = 'block_for_include/block_for_tricks_ajax.html.twig';

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
        $contextView = [
            'tricksToDisplay' => $this->getTricksToDisplay($request),
            'hasTricksToDisplay' => $this->hasTricksToDisplay($request),
        ];
        return new Response(
            $this->environment->render(
                self::BLOCK_FOR_TRICKS_TWIG_PATH,
                $contextView
            )
        );
    }
}
