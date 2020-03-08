<?php

namespace App\Actions\Comments;

use App\Actions\Interfaces\Comments\MoreComInterface;
use App\Actions\OwnAbstractController;
use App\Entity\Comments;
use App\Form\FigureType;
use App\Services\FormResolverComment;
use App\Services\FormResolverMedias;
use App\Services\FormResolverPasswordRecovery;
use App\Services\FormResolverRecoveryPassword;
use App\Services\FormResolverRegistration;
use App\Services\FormResolverTricks;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
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
class MoreCom implements MoreComInterface
{
    /** @var UrlGeneratorInterface  */
    private $router;
    /** @var Filesystem  */
    private $filesystem;
    /** @var Environment  */
    private $environment;
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var TokenStorageInterface  */
    private $tokenStorage;

    /**
     * MoreCom constructor.
     * @param UrlGeneratorInterface $router
     * @param Filesystem $filesystem
     * @param Environment $environment
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        UrlGeneratorInterface $router,
        Filesystem $filesystem,
        Environment $environment,
        EntityManagerInterface $manager,
        TokenStorageInterface $tokenStorage
    ) {
        $this->router = $router;
        $this->filesystem = $filesystem;
        $this->environment = $environment;
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
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
        $figureId = $request->query->get('figureid');
        $offset = $pageId * Comments::LIMIT_PER_PAGE - Comments::LIMIT_PER_PAGE;
        $nb_coms = $this->manager->getRepository(Comments::class)->findBy(['idfigure' => $figureId]);
        if ($nb_coms > Comments::LIMIT_PER_PAGE) {
            $rest = false;
        } else {
            $rest = $pageId * Comments::LIMIT_PER_PAGE < $nb_coms;
        }

        /** @var Comments $comsToShow */
        $comsToShow = $this->manager->getRepository(Comments::class)
            ->findBy(['idfigure' => $figureId], [], Comments::LIMIT_PER_PAGE, $offset);

        return new Response(
            $this->environment->render(
                'block_for_include/block_for_coms_ajax.html.twig',
                [
                'user' => $this->tokenStorage->getToken()->getUser(),
                'comsToShow' => $comsToShow,
                'rest' => $rest
                ]
            )
        );
    }
}
