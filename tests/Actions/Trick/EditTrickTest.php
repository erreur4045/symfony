<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 11 03 2020
 * Project : symfonytestversion
 * File : EditTrickTest.php
 * PHP Version : 7.3.5
 */

namespace App\Tests\Actions\Trick;

use App\Tests\AbstractWebCasse;

class EditTrickTest extends AbstractWebCasse
{
    protected function setUp(): void
    {
        $this->reloadDataFixtures();
    }

    public function testEditTrickPage()
    {
        $this->ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/edit/mute');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
