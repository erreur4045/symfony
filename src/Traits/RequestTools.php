<?php


namespace App\Traits;


use App\Entity\Figure;
use App\Repository\CommentsRepository;
use App\Repository\FigureRepository;
use Symfony\Component\HttpFoundation\Request;

trait RequestTools
{

    /** @var FigureRepository */
    private $tricksRepo;
    /** @var CommentsRepository */
    private $commentsRepo;

    /**
     * @param Request $request
     * @return mixed
     */
    protected function getSlugFrom(Request $request)
    {
        return $request->attributes->get('slug');
    }

    /**
     * @param Request $request
     * @return Figure|object|null
     */
    public function getTrick(Request $request)
    {
        $trickId = $this->getTrickFromIdComment($request)->getId();
        return $this->tricksRepo->findOneBy(['id' => $trickId]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getIdCommentFrom(Request $request)
    {
        return $request->get('id');
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
     * @param Request $request
     * @return mixed
     */
    public function getTrickId(Request $request)
    {
        return $request->query->get('figureid');
    }

    /**
     * @param Request $request
     * @return Figure
     */
    public function getTrickFromIdComment(Request $request) :Figure
    {
        $comment = $this->commentsRepo->find($this->getIdCommentFrom($request));
        return $comment->getFigure();
    }
}