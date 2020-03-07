<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : DeletePictureInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Interfaces\Medias\Picture;


use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/media/delete/picture/{picture}", name="delete.image")
 * @IsGranted("ROLE_USER")
 */
interface DeletePictureInterface
{
    /**
     * DeletePicture constructor.
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param Filesystem $filesystem
     * @param string $tricksPicturesDirectory
     * @param UrlGeneratorInterface $router
     */
    public function __construct(
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        Filesystem $filesystem,
        string $tricksPicturesDirectory,
        UrlGeneratorInterface $router
    );

    public function __invoke($picture);
}