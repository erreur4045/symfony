<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 11 03 2020
 * Project : symfonytestversion
 * File : VideolinkTest.php
 * PHP Version : 7.3.5
 */

namespace App\Tests\Entity;

use App\Entity\Figure;
use App\Entity\Videolink;
use PHPUnit\Framework\TestCase;

class VideolinkTest extends TestCase
{
    protected $video;

    protected function setUp()
    {
        parent::setUp();
        $this->video = new Videolink();
    }

    public function testAttributes()
    {
        $this->video->setFigure(new Figure());
        static::assertEquals(new Figure(), $this->video->getFigure());
        $this->video->setLinkvideo('test');
        static::assertEquals('test', $this->video->getLinkvideo());
    }
}
