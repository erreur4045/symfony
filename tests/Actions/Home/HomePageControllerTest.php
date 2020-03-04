<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : HomePageControllerTest.php
 * PHP Version : 7.3.5
 */

namespace App\Tests\Actions\Home;


use App\DataFixtures\AppFixtures;
use App\Tests\AbstractTestCase;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomePageControllerTest extends AbstractTestCase
{
    /** @var AppFixtures */
    protected $fixture;
    /** @var ObjectManager */
    protected $manager;

    protected function setUp()
    {
        parent::setUp();
        $container = self::$container;
        $manager = $container->get('doctrine.orm.entity_manager');
        $this->fixture->load($manager);
    }

    public function testIndex()
    {
        $this->ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}