<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 11 03 2020
 * Project : symfonytestversion
 * File : PictureslinksTest.php
 * PHP Version : 7.3.5
 */

namespace App\Tests\Entity;

use App\Entity\Figure;
use App\Entity\Pictureslink;
use PHPUnit\Framework\TestCase;

class PictureslinksTest extends TestCase
{
    protected $picture;

    protected function setUp()
    {
        parent::setUp();
        $this->picture = new Pictureslink();
    }

    public function testAttributes()
    {
        $this->picture->setAlt('test');
        $this->picture->setFirstImage(true);
        $this->picture->setLinkpictures('www');
        $this->picture->setFigure(new Figure());
        static::assertEquals('test', $this->picture->getAlt());
        static::assertEquals(true, $this->picture->getFirstImage());
        static::assertEquals('www', $this->picture->getLinkpictures());
        static::assertEquals(new Figure(), $this->picture->getFigure());
    }
}