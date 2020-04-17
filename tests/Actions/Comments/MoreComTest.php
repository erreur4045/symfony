<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 11 03 2020
 * Project : symfonytestversion
 * File : MoreComTest.php
 * PHP Version : 7.3.5
 */

namespace App\Tests\Comments;

use App\Entity\Figure;
use App\Tests\AbstractWebCase;

class MoreComTest extends AbstractWebCase
{
    protected function setUp(): void
    {
        $this->reloadDataFixtures();
    }
    public function testIndex()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $figure = $entityManager->getRepository(Figure::class)->findAll();
        $this->ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/tricks/details/more_com?page=2&figureid' . $figure[0]->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
