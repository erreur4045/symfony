<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 11 03 2020
 * Project : symfonytestversion
 * File : DeleteComTest.php
 * PHP Version : 7.3.5
 */

namespace App\Tests\Comments;

use App\Entity\Comments;
use App\Tests\AbstractWebCase;

class DeleteComTest extends AbstractWebCase
{
    protected function setUp(): void
    {
        parent::setUp();
        parent::reloadDataFixtures();
    }

    public function testDeleteComWithUnexistCom()
    {
        $this->ensureKernelShutdown();
        $client = static::createClient();
        $client->request('DELETE', '/deletecom/4564');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testDeleteComWithExistCom()
    {
        $this->ensureKernelShutdown();
        $client = static::createClient();
        $kernel = static::createKernel();
        $kernel->boot();
        $entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $coms = $entityManager->getRepository(Comments::class)->findAll();
        $client->request('DELETE', '/deletecom/' . $coms[0]->getId());
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
