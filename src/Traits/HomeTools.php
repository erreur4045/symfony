<?php


namespace App\Traits;

use App\Entity\Figure;
use Symfony\Component\HttpFoundation\Request;

/**
 * Trait HomeToolsTrait
 * @package App\Traits\Home
 */
trait HomeTools
{
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
        return $this->getPageId($request) * Figure::LIMIT_PER_PAGE < $this->tricksRepo->countTricks();
    }
    /**
     * @param Request $request
     * @return mixed
     */
    public function getPageId(Request $request)
    {
        return $request->query->get('page');
    }
}
