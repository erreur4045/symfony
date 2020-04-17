<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 11 03 2020
 * Project : symfonytestversion
 * File : AbstractWebCasse.php
 * PHP Version : 7.3.5
 */

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractWebCase extends WebTestCase
{
    protected function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $manager = $kernel->getContainer()->get('doctrine')->getManager();
        $schemaTool = new SchemaTool($manager);
        $schemaTool->dropDatabase($manager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($manager->getMetadataFactory()->getAllMetadata());
    }

    protected static function reloadDataFixtures()
    {

        $kernel = static::createKernel();
        $kernel->boot();
        $entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $loader = new Loader();
        foreach (self::getFixtures() as $fixture) {
            $loader->addFixture($fixture);
        }

        $purger = new ORMPurger();
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $executor = new ORMExecutor($entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }

    protected static function getFixtures()
    {
        return [
            new AppFixturesTest()
        ];
    }
}
