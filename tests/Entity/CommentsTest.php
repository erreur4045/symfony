<?php
/**
 * Create by Maxime THIERRY
 * Email : maximethi@hotmail.fr
 *
 * Date : 11 03 2020
 * Project : symfonytestversion
 * File : CommentsTest.php
 * PHP Version : 7.3.5
 */

namespace App\Tests\Entity;

use App\Entity\Comments;
use PHPUnit\Framework\TestCase;

class CommentsTest extends TestCase
{
    private $comment;

    protected function setUp()
    {
        parent::setUp();
        $this->comment = new Comments();
    }

    public function testAttributes()
    {
        $date = new \DateTime('2020-03-11 20:36:43');
        $this->comment->setDatecreate($date);
        $this->comment->setDateupdate($date);
        $this->comment->setText('test');
        static::assertEquals($date, $this->comment->getDatecreate());
        static::assertEquals($date, $this->comment->getDateupdate());
        static::assertEquals('test', $this->comment->getText());
    }
}