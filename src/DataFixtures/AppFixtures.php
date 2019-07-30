<?php

namespace App\DataFixtures;

use App\Entity\Comments;
use App\Entity\Figure;
use App\Entity\Figuregroup;
use App\Entity\Pictureslink;
use App\Entity\User;
use App\Entity\Videolink;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $figuregroup = new Figuregroup();
            $figuregroup->setName($faker->sentence($nbWords = 6, $variableNbWords = true));
            $manager->persist($figuregroup);
            for ($j = 0; $j <= 2; $j++) {
                $user = new User();
                $user->setName($faker->lastName());
                $user->setSurname($faker->firstName());
                $user->setDatesub($faker->dateTimeInInterval('-30 days', '+5 dayes'));
                $user->setPassword($faker->password());
                $user->setGrade(random_int(1, 2));
                $user->setMail($faker->safeEmail);
                $user->setPicturelink($faker->imageUrl($width = 640, $height = 480));
                $manager->persist($user);

                for ($k = 0; $k <= 2; $k++) {
                    $figure = new Figure();
                    $figure->setName($faker->sentence())
                        ->setUser($user)
                        ->setIdfiguregroup($figuregroup)
                        ->setDescription($faker->sentence());
                    $manager->persist($figure);
                    for ($p = 0; $p <= 2; $p++) {
                        $linkimage = new Pictureslink();
                        $linkimage->setFigure($figure)
                            ->setLinkpictures($faker->imageUrl($width = 640, $height = 480));
                        $manager->persist($linkimage);

                        $videolink = new Videolink();
                        $videolink->setFigure($figure)
                            ->setLinkvideo($faker->imageUrl($width = 640, $height = 480));
                        $manager->persist($videolink);
                    }

                    for ($l = 0; $l < 2; $l++) {
                        $comment = new Comments();
                        $comment->setText($faker->sentence())
                            ->setDatecreate($faker->dateTime())
                            ->setDateupdate($faker->dateTime())
                            ->setIdfigure($figure)
                            ->setUser($user);
                        $manager->persist($comment);

                    }
                }
            }
        }

        $manager->flush();
    }

}
