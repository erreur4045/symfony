<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : AbstractController.php
 * PHP Version : 7.3.5
 */

namespace App\Actions;

use App\Form\FigureType;
use App\Services\FormResolverComment;
use App\Services\FormResolverMedias;
use App\Services\FormResolverPasswordRecovery;
use App\Services\FormResolverRegistration;
use App\Services\FormResolverTricks;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

class OwnAbstractController
{
    /** @var Environment  */
    protected $templating;
    /** @var FigureType  */
    protected $figureType;
    /** @var FormFactoryInterface  */
    protected $formFactory;
    /** @var UrlGeneratorInterface  */
    protected $router;
    /** @var Filesystem  */
    protected $filesystem;
    /** @var Environment  */
    protected $environment;
    /** @var string  */
    protected $tricksPicturesDirectory;
    /** @var FormResolverMedias  */
    protected $formResolverMedias;
    /** @var EntityManagerInterface  */
    protected $manager;
    /** @var FlashBagInterface  */
    protected $bag;
    /** @var TokenStorageInterface  */
    protected $tokenStorage;
    /** @var FormResolverComment  */
    protected $formResolverComment;
    /** @var FormResolverRegistration */
    protected $fromResolverRegistration;
    /** @var  FormResolverPasswordRecovery */
    protected $formResolverPasswordRecovery;
    /** @var FormResolverTricks */
    protected $formResolverTricks;

    /**
     * OwnAbstractController constructor.
     * @param Environment $templating
     * @param FigureType $figureType
     * @param FormFactoryInterface $formFactory
     * @param UrlGeneratorInterface $router
     * @param Filesystem $filesystem
     * @param Environment $environment
     * @param string $tricksPicturesDirectory
     * @param FormResolverMedias $formResolverMedias
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param TokenStorageInterface $tokenStorage
     * @param FormResolverComment $formResolverComment
     * @param FormResolverRegistration $fromResolverRegistration
     * @param FormResolverPasswordRecovery $formResolverPasswordRecovery
     * @param FormResolverTricks $formResolverTricks
     */
    public function __construct(
        Environment $templating,
        FigureType $figureType,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $router,
        Filesystem $filesystem,
        Environment $environment,
        string $tricksPicturesDirectory,
        FormResolverMedias $formResolverMedias,
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        TokenStorageInterface $tokenStorage,
        FormResolverComment $formResolverComment,
        FormResolverRegistration $fromResolverRegistration,
        FormResolverPasswordRecovery $formResolverPasswordRecovery,
        FormResolverTricks $formResolverTricks
    ) {
        $this->templating = $templating;
        $this->figureType = $figureType;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->filesystem = $filesystem;
        $this->environment = $environment;
        $this->tricksPicturesDirectory = $tricksPicturesDirectory;
        $this->formResolverMedias = $formResolverMedias;
        $this->manager = $manager;
        $this->bag = $bag;
        $this->tokenStorage = $tokenStorage;
        $this->formResolverComment = $formResolverComment;
        $this->fromResolverRegistration = $fromResolverRegistration;
        $this->formResolverPasswordRecovery = $formResolverPasswordRecovery;
        $this->formResolverTricks = $formResolverTricks;
    }
}