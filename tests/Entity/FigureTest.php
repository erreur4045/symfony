<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 11 03 2020
 * Project : symfonytestversion
 * File : FigureTest.php
 * PHP Version : 7.3.5
 */

namespace App\Tests\Entity;

use App\Entity\Figure;
use App\Entity\Figuregroup;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class FigureTest extends TestCase
{
    private $figure;

    protected function setUp()
    {
        parent::setUp();
        $this->figure = new Figure();
    }

    public function testAttributes()
    {
        $date = new \DateTime('2020-03-11 20:36:43');
        $this->figure->setDatecreate($date);
        $this->figure->setDateupdate($date);
        $this->figure->setName('test');
        $this->figure->setDescription('test');
        $this->figure->setUser(new User());
        $this->figure->setIdfiguregroup(new Figuregroup());
        static::assertEquals($date, $this->figure->getDatecreate());
        static::assertEquals($date, $this->figure->getDateupdate());
        static::assertEquals('test', $this->figure->getName());
        static::assertEquals('test', $this->figure->getDescription());
        static::assertEquals(new User(), $this->figure->getUser());
        static::assertEquals(new Figuregroup(), $this->figure->getIdfiguregroup());
    }
}
