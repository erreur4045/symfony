<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 11 03 2020
 * Project : symfonytestversion
 * File : GetTrickTest.php
 * PHP Version : 7.3.5
 */

namespace App\Tests\Actions\Trick;

use App\Tests\AbstractWebCase;

class GetTrickTest extends AbstractWebCase
{
    protected function setUp(): void
    {
        $this->reloadDataFixtures();
    }

    public function testGetTrick()
    {
        $this->ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/tricks/details/mute');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
