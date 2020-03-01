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

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

class OwnAbstractController
{
    /**
     *
     *
     * @var EntityManagerInterface
     **/
    protected $manager;

    /**
     *
     *
     * @var Environment
     **/
    protected $environment;

    public function __construct(
        EntityManagerInterface $manager,
        Environment $environment
    ) {
        $this->manager = $manager;
        $this->environment = $environment;
    }
}