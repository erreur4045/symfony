<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 11 03 2020
 * Project : symfonytestversion
 * File : GetMoreTrickTest.php
 * PHP Version : 7.3.5
 */

namespace App\Tests\Actions\Home;


use App\Tests\AbstractWebCasse;

class GetMoreTrickTest extends AbstractWebCasse
{
    protected function setUp(): void
    {
        $this->reloadDataFixtures();
    }
    public function testIndex()
    {
        $this->ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/more_tricks?page=2');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}