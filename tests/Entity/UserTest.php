<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 11 03 2020
 * Project : symfonytestversion
 * File : UserTest.php
 * PHP Version : 7.3.5
 */

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected $user;

    protected function setUp()
    {
        parent::setUp();
        $this->user = new User();
    }

    public function testAttributes()
    {
        $date = new \DateTime('2020-03-11 20:36:43');
        $this->user->setName('test');
        static::assertEquals('test', $this->user->getName());
        $this->user->setPassword('test');
        static::assertEquals('test', $this->user->getPassword());
        $this->user->setDatesub($date);
        static::assertEquals($date, $this->user->getDatesub());
        $this->user->setId(8);
        static::assertEquals(8, $this->user->getId());
        $this->user->setMail('test');
        static::assertEquals('test', $this->user->getMail());
        $this->user->setProfilePicture('test');
        static::assertEquals('test', $this->user->getProfilePicture());
        $this->user->setToken('test');
        static::assertEquals('test', $this->user->getToken());
    }
}