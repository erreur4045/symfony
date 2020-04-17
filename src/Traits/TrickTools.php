<?php


namespace App\Traits;

use App\Entity\Comments;
use App\Entity\Figure;
use Symfony\Component\HttpFoundation\Request;

trait TrickTools
{


    /**
     * @param Figure $figure
     * @return false|float
     */
    protected function getPageMaxCommentFrom(Figure $figure)
    {
        return ceil(count($this->commentRepo->findBy(['figure' => $figure->getId()])) / Comments::LIMIT_PER_PAGE);
    }

    /**
     * @param Figure $figure
     * @return bool
     */
    protected function isOtherMedia(Figure $figure)
    {
        return empty($this->pictureRepo->isOneOtherFirstImage($figure)) && empty($this->videoRepo->getByTrick($figure));
    }

    /**
     * @param Request $request
     * @return Figure|null
     */
    protected function getFigureFrom(Request $request)
    {
        $slug = $request->attributes->get('slug');
        return $this->figureRepo->findOneBy(['slug' => $slug]);
    }
}
