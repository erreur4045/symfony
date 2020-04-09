<?php


namespace App\Actions\Home;

use App\Entity\Figure;
use App\Repository\FigureRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Trait HomeToolsTrait
 * @package App\Actions\Home
 */
trait HomeToolsTrait
{
    /** @var FigureRepository */
    private $tricksRepo;

    /**
     * HomeToolsTrait constructor.
     * @param FigureRepository $tricksRepo
     */
    public function __construct(FigureRepository $tricksRepo)
    {
        $this->tricksRepo = $tricksRepo;
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
     * @param Request $request
     * @return bool
     */
    public function hasTricksToDisplay(Request $request): bool
    {
        return $this->getPageId($request) * Figure::LIMIT_PER_PAGE < $this->countTricks();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getTricksToDisplay(Request $request): array
    {
        $pageId = $this->getPageId($request);
        return $this->tricksRepo->findBy(
            [],
            [],
            Figure::LIMIT_PER_PAGE,
            $this->computeOffset($pageId)
        );
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getPageId(Request $request)
    {
        return $request->query->get('page');
    }

    /**
     * @return int
     */
    public function countTricks(): int
    {
        return $this->tricksRepo->count([]);
    }
}
