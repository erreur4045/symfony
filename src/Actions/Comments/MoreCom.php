<?php

namespace App\Actions\Comments;

use App\Entity\Comments;
use App\Traits\RequestToolsTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class MoreCom
 * @package App\Actions\Comments
 * @Route("tricks/details/more_com", name="more.coms")
 *
 */
class MoreCom
{
    use CommentsToolsTrait, RequestToolsTrait;

    /** @var Environment  */
    private $environment;

    /**
     * MoreCom constructor.
     * @param Environment $environment
     */
    public function __construct(Environment $environment) {
        $this->environment = $environment;
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
        $pageId = $this->getPageId($request);
        $trickId = $this->getTrickId($request);
        $offset = $pageId * Comments::LIMIT_PER_PAGE - Comments::LIMIT_PER_PAGE;
        $countComments = $this->countCommentsByIdTrick($trickId);
        // todo : à voir avec Mehmet
        if ($countComments > Comments::LIMIT_PER_PAGE) {
            $rest = false;
        } else {
            $rest = $pageId * Comments::LIMIT_PER_PAGE < $countComments;
        }

        return new Response(
            $this->environment->render(
                'block_for_include/block_for_coms_ajax.html.twig',
                [
                'user' => $this->getUserFromToken(),
                'commentsToShow' => $this->getCommentsToShow($trickId, $offset),
                'rest' => $rest
                ]
            )
        );
    }
}
