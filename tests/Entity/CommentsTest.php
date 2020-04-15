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
use DateTime;
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
        $date = new DateTime('2020-03-11 20:36:43');
        $this->comment->setDateCreate($date);
        $this->comment->setDateUpdate($date);
        $this->comment->setText('test');
        static::assertEquals($date, $this->comment->getDateCreate());
        static::assertEquals($date, $this->comment->getDateUpdate());
        static::assertEquals('test', $this->comment->getText());
    }
}
