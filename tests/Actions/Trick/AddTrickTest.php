<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 11 03 2020
 * Project : symfonytestversion
 * File : AddTrickTest.php
 * PHP Version : 7.3.5
 */

namespace App\Tests\Actions\Trick;

use App\Tests\AbstractWebCasse;

class AddTrickTest extends AbstractWebCasse
{
    protected function setUp(): void
    {
        $this->reloadDataFixtures();
    }

    public function testPageAddTrick()
    {
        $this->ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/addtrick');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
