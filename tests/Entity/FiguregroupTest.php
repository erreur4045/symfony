<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 11 03 2020
 * Project : symfonytestversion
 * File : FiguregroupTest.php
 * PHP Version : 7.3.5
 */

namespace App\Tests\Entity;


use App\Entity\Figure;
use App\Entity\Figuregroup;
use PHPUnit\Framework\TestCase;

class FiguregroupTest extends TestCase
{
    private $figuregroup;

    protected function setUp()
    {
        parent::setUp();
        $this->figuregroup = new Figuregroup();
    }

    public function testAttributes()
    {
        $this->figuregroup->setName('test');
        static::assertEquals('test', $this->figuregroup->getName());
    }
}