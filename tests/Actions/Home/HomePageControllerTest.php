<?php

/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 01 03 2020
 * Project : symfonytestversion
 * File : HomePageControllerTest.php
 * PHP Version : 7.3.5
 */

namespace App\Tests\Actions\Home;

use App\Tests\AbstractTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $this->ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testIndexTrick()
    {
        $this->ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/#tricks');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testIndexBadAncre()
    {
        $this->ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/#sdfs');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testIndexBadRoute()
    {
        $this->ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/sdf');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}