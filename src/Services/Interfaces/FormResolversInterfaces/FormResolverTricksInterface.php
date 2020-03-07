<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 07 03 2020
 * Project : symfonytestversion
 * File : FormResolverTricksInterface.php
 * PHP Version : 7.3.5
 */

namespace App\Services\Interfaces\FormResolversInterfaces;

use App\Entity\Figure;
use App\Entity\User;
use Symfony\Component\Form\FormInterface;

interface FormResolverTricksInterface
{
    /**
     * @param FormInterface $form
     * @param User $user
     * @throws \Exception
     */
    public function addTrick(
        FormInterface $form,
        User $user
    );

    /**
     * @param Figure $figure
     * @throws \Exception
     */
    public function updateTrick(Figure $figure);

    /**
     * @param $figure
     * @param string $patternYT
     * @param $matches
     */
    public function addVideosToFigure(
        $figure,
        string $patternYT
    );

    /**
     * @param $figure
     */
    public function addFigureLessPictures($figure): void;

    /**
     * @param $figure
     */
    public function hasFirstImage($figure): void;

    /**
     * @param $figure
     */
    public function setPicturesToFigure($figure): void;
}
