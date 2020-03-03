<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : AbstractTestCase.php
 * PHP Version : 7.3.5
 */

namespace App\Tests;


use App\DataFixtures\AppFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AbstractTestCase extends WebTestCase
{
    /** @var KernelBrowser|null */
    protected $clientAPI;
    /** @var EntityManagerInterface */
    protected $manager;
    /** @var AppFixtures */
    protected $fixture;
    /** @var ObjectManager */
    protected $managerLoad;
    /** @var  Application $application */
    protected static $application;

    protected function setUp()
    {
        $this->clientAPI = self::createClient([], [
            'HTTP_HOST' => '127.0.0.1:8000',
            'HTTPS' => true
        ]);
        $container = self::$container;
        $this->manager = $container->get('doctrine.orm.entity_manager');

        $schemaTool = new SchemaTool($this->manager);
        $schemaTool->dropDatabase($this->manager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->manager->getMetadataFactory()->getAllMetadata());
    }

    /**
     * @param string|null $method
     * @param string|null $url
     * @param string $bodyRequest
     * @return Response
     */
    protected function request(
        string $method,
        string $url,
        $identifier = null,
        $password = null,
        string $bodyRequest = null
    ): Response {
        if ($identifier and $password) {
            $this->clientAPI->setServerParameter('HTTP_Authorization',
                sprintf('Bearer %s', $this->authenticate($identifier, $password)));
        }

        $this->clientAPI->request($method, $url, [], [], ['CONTENT_TYPE' => 'application/json'], $bodyRequest);
        return $this->clientAPI->getResponse();
    }

    protected function authenticate(
        $identifier,
        $password
    ) {
        $this->clientAPI->request(
            'POST',
            '/api/login_check',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(
                [
                    'username' => $identifier,
                    'password' => $password,
                ]
            )
        );
        $responseContent = json_decode($this->clientAPI->getResponse()->getContent(), true);
        return $responseContent['token'];
    }

    protected static function reloadDataFixtures(
        UserPasswordEncoderInterface $encoder,
        string $tricksPicturesDirectory,
        bool $lts = false
    ): void {
        $kernel = static::createKernel();
        $kernel->boot();
        $entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $loader = new Loader();
        foreach (self::getFixtures($lts, $encoder, $tricksPicturesDirectory) as $fixture) {
            $loader->addFixture($fixture);
        }

        $purger = new ORMPurger();
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $executor = new ORMExecutor($entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }

    protected static function getFixtures(
        $lts,
        UserPasswordEncoderInterface $encoder,
        string $tricksPicturesDirectory
    ): iterable {
        if ($lts == true) {
            return [
                new AppFixturesLts(),
            ];
        } else {
            return [
                new AppFixtures($encoder, $tricksPicturesDirectory),
            ];
        }
    }
}