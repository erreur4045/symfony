<?php

namespace App\Actions\Home;

use App\Repository\FigureRepository;
use App\Responder\Interfaces\ResponderInterface;
use App\Traits\HomeTools;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @Route("/more_tricks", name="more.tricks")
 */
class GetMoreTricks
{
    use HomeTools;

    const BLOCK_FOR_TRICKS_TWIG_PATH = 'block_for_include/block_for_tricks_ajax.html.twig';

    private Environment $environment;
    private EntityManagerInterface $manager;
    private FigureRepository $tricksRepo;
    private ResponderInterface $responder;

    /**
     * GetMoreTricks constructor.
     * @param Environment $environment
     * @param EntityManagerInterface $manager
     * @param FigureRepository $tricksRepo
     * @param ResponderInterface $responder
     */
    public function __construct(
        Environment $environment,
        EntityManagerInterface $manager,
        FigureRepository $tricksRepo,
        ResponderInterface $responder
    ) {
        $this->environment = $environment;
        $this->manager = $manager;
        $this->tricksRepo = $tricksRepo;
        $this->responder = $responder;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $contextView = [
            'tricksToDisplay' => $this->tricksRepo->getTricksToDisplay($request),
            'hasTricksToDisplay' => $this->hasTricksToDisplay($request),
        ];
        return $this->responder->render(
            self::BLOCK_FOR_TRICKS_TWIG_PATH,
            $contextView
        );
    }
}
