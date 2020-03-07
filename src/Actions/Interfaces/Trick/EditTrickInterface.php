<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : EditTrickInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Interfaces\Trick;


use App\Services\FormResolvers\FormResolverTricks;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * @Route("/edit/{slug}", name="edit.trick")
 * @IsGranted("ROLE_USER")
 */
interface EditTrickInterface
{
    /**
     * EditTrick constructor.
     * @param Environment $templating
     * @param UrlGeneratorInterface $router
     * @param EntityManagerInterface $manager
     * @param FormResolverTricks $formResolverTricks
     */
    public function __construct(
        Environment $templating,
        UrlGeneratorInterface $router,
        EntityManagerInterface $manager,
        FormResolverTricks $formResolverTricks
    );

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function __invoke(Request $request);
}