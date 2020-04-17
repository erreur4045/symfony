<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 11 03 2020
 * Project : symfonytestversion
 * File : EditComTest.php
 * PHP Version : 7.3.5
 */

namespace App\Tests\Comments;

use App\Tests\AbstractWebCase;

class EditComTest extends AbstractWebCase
{
    protected function setUp(): void
    {
        $this->reloadDataFixtures();
    }
    public function testEditComPageWithUnexsistCom()
    {
        $this->ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/editcom/78979');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
