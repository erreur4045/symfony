<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 11 03 2020
 * Project : symfonytestversion
 * File : DeleteTrickTest.php
 * PHP Version : 7.3.5
 */

namespace App\Tests\Actions\Trick;

use App\Tests\AbstractWebCasse;

class DeleteTrickTest extends AbstractWebCasse
{
    protected function setUp(): void
    {
        $this->reloadDataFixtures();
    }

    public function testPageDeleteTrick()
    {
        $this->ensureKernelShutdown();
        $client = static::createClient();
        $client->request('DELETE', '/delete/mute');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}