<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : DeleteComInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Actions\Interfaces\Comments;

use App\Entity\Comments;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/deletecom/{id}", name="delete.comment")
 * @IsGranted("ROLE_USER")
 */
interface DeleteComInterface
{
    /**
     * DeleteCom constructor.
     * @param UrlGeneratorInterface $router
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        UrlGeneratorInterface $router,
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        TokenStorageInterface $tokenStorage
    );

    /**
     * @param Comments $comment
     * @param Request $request
     * @return RedirectResponse
     */
    public function __invoke(
        Comments $comment,
        Request $request
    );
}
