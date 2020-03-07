<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : GetTrickInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Interfaces\Trick;


use App\Services\FormResolvers\FormResolverComment;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

/**
 * @Route("/tricks/details/{slug}", name="trick")
 */
interface GetTrickInterface
{
    /**
     * GetTrick constructor.
     * @param Environment $templating
     * @param UrlGeneratorInterface $router
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param TokenStorageInterface $tokenStorage
     * @param FormResolverComment $formResolverComment
     */
    public function __construct(
        Environment $templating,
        UrlGeneratorInterface $router,
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        TokenStorageInterface $tokenStorage,
        FormResolverComment $formResolverComment
    );

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws EntityNotFoundException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(Request $request);
}