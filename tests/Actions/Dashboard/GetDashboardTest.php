<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 11 03 2020
 * Project : symfonytestversion
 * File : GetDashboardTest.php
 * PHP Version : 7.3.5
 */

namespace App\Tests\Dashboard;

use App\Tests\AbstractWebCase;

class GetDashboardTest extends AbstractWebCase
{
    public function setUp(): void
    {
        $this->reloadDataFixtures();
    }

    public function testGetDashboardLessAuht()
    {

        $this->ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/dashboard');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
