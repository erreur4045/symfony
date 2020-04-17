<?php

namespace App\Actions\Comments;

use App\Repository\CommentsRepository;
use App\Repository\FigureRepository;
use App\Responder\Interfaces\ResponderInterface;
use App\Traits\CommentsTools;
use App\Traits\RequestTools;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

/**
 * Class MoreCom
 * @package App\Actions\Comments
 * @Route("tricks/details/more_com", name="more.coms")
 *
 */
class MoreComments
{

    use CommentsTools, RequestTools ;

    const BLOCK_COMMENTS_TWIG = 'block_for_include/block_for_coms_ajax.html.twig';

    private EntityManagerInterface $manager;
    private TokenStorageInterface $tokenStorage;
    private FigureRepository $tricksRepo;
    private CommentsRepository $commentsRepo;
    private UrlGeneratorInterface $router;
    private Environment $environment;
    private ResponderInterface $responder;

    /**
     * MoreComments constructor.
     * @param EntityManagerInterface $manager
     * @param TokenStorageInterface $tokenStorage
     * @param FigureRepository $tricksRepo
     * @param CommentsRepository $commentsRepo
     * @param UrlGeneratorInterface $router
     * @param Environment $environment
     * @param ResponderInterface $responder
     */
    public function __construct(
        EntityManagerInterface $manager,
        TokenStorageInterface $tokenStorage,
        FigureRepository $tricksRepo,
        CommentsRepository $commentsRepo,
        UrlGeneratorInterface $router,
        Environment $environment,
        ResponderInterface $responder
    ) {
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
        $this->tricksRepo = $tricksRepo;
        $this->commentsRepo = $commentsRepo;
        $this->router = $router;
        $this->environment = $environment;
        $this->responder = $responder;
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request) :Response
    {
        $pageId = $this->getPageId($request);
        $trickId = $this->getTrickId($request);
        $offset = $this->computeOffsetFrom($pageId);
        $countComments = $this->commentsRepo->countCommentsByIdTrick($trickId);
        $contextView = [
            'user' => $this->getConnectedUser(),
            'commentsToShow' => $this->commentsRepo->getCommentsToShow($trickId, $offset),
            'rest' => $this->isRest($countComments, $pageId)
        ];
        return $this->responder->render(self::BLOCK_COMMENTS_TWIG, $contextView);
    }
}
