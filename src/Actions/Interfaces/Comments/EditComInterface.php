<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : EditComInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Interfaces\Comments;


use App\Services\FormResolvers\FormResolverComment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * @Route("/editcom/{id}", name="edit.comment")
 * @IsGranted("ROLE_USER")
 */
interface EditComInterface
{
    /**
     * EditCom constructor.
     * @param Environment $environment
     * @param UrlGeneratorInterface $router
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param TokenStorageInterface $tokenStorage
     * @param FormResolverComment $formResolverComment
     */
    public function __construct(
        Environment $environment,
        UrlGeneratorInterface $router,
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        TokenStorageInterface $tokenStorage,
        FormResolverComment $formResolverComment
    );

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(Request $request);
}