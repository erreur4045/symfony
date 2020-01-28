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
use Doctrine\ORM\EntityManagerInterface;
use Faker;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    /**
     * Allows the password to be encoded for storage in the database
     *
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * Path to the folder that contains the pictures
     *
     * @var string
     */
    private $tricksPicturesDirectory;

    public function __construct(
        UserPasswordEncoderInterface $encoder,
        string $tricksPicturesDirectory
    ) {
        $this->encoder = $encoder;
        $this->tricksPicturesDirectory = $tricksPicturesDirectory;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $figureGroupeNames = [
            0 => 'Les grabs',
            1 => 'Les rotations',
            2 => 'Les flips',
            3 => 'Les rotations désaxées',
            4 => 'Les slides',
            5 => 'Old school'
        ];

        $videosYoutube = [
            0 => 'https://www.youtube.com/embed/Zc8Gu8FwZkQ',
            1 => 'https://www.youtube.com/embed/0uGETVnkujA',
            2 => 'https://www.youtube.com/embed/G9qlTInKbNE',
            3 => 'https://www.youtube.com/embed/8AWdZKMTG3U',
            4 => 'https://www.youtube.com/embed/SQyTWk7OxSI'
        ];

        foreach ($figureGroupeNames as $name) {
            $figuregroupe = new Figuregroup();
            $figuregroupe->setName($name);
            $manager->persist($figuregroupe);
        }

        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setProfilePicture(Pictureslink::PICTURELINKUSERRAND)
                ->setDatesub($faker->dateTimeInInterval('-30 days', '+5 dayes'))
                ->setName($faker->lastName())
                ->setMail($faker->safeEmail)
                ->setPassword($this->encoder->encodePassword($user, "testpass"));
                    $manager->persist($user);
            for ($k = 0; $k <= 2; $k++) {
                $figure = new  Figure();
                $figure->setName($faker->sentence(3))
                    ->setUser($user)
                    ->setDatecreate(
                        $faker->dateTimeInInterval('-30 days', '+5 dayes')
                    )
                    ->setDescription(implode(" ", $faker->sentences(4)))
                    ->setIdfiguregroup($figuregroupe);
                $manager->persist($figure);
                for ($l = 0; $l == 0; $l++) {
                        $filesystem = new Filesystem();
                        $pictureDefault = new Pictureslink();
                        $randId = rand(0, 2);
                        $randPicture = Pictureslink::PICTURELINKTRICKRAND[$randId];
                        $newPicture = $randPicture . '-' . uniqid() . '.jpg';
                        $filesystem->copy(
                            $this->tricksPicturesDirectory . $randPicture,
                            $this->tricksPicturesDirectory . $newPicture
                        );
                        $pictureDefault->setAlt($faker->sentence)
                            ->setFirstImage(1)
                            ->setFigure($figure)
                            ->setLinkpictures($newPicture);
                        $manager->persist($pictureDefault);
                }
                for ($l = 0; $l <= 2; $l++) {
                    $filesystem = new Filesystem();
                    $picture = new Pictureslink();
                    $randId = rand(0, 2);
                    $randPicture = Pictureslink::PICTURELINKTRICKRAND[$randId];
                    $newPicture = $randPicture . '-' . uniqid() . '.jpg';
                    $filesystem->copy(
                        $this->tricksPicturesDirectory . $randPicture,
                        $this->tricksPicturesDirectory . $newPicture
                    );
                    $picture->setAlt($faker->sentence)
                        ->setFirstImage(0)
                        ->setFigure($figure)
                        ->setLinkpictures($newPicture);
                    $manager->persist($picture);
                }
                for ($m = 0; $m <= 2; $m++) {
                            $video = new Videolink();
                            $video->setFigure($figure)
                                ->setLinkvideo(
                                    $videosYoutube[array_rand($videosYoutube)]
                                );
                            $manager->persist($video);
                }
                for ($n = 0; $n <= 5; $n++) {
                    $comment = new Comments();
                    $comment->setDatecreate(
                        $faker->dateTimeInInterval('-30 days', '+5 dayes')
                    )
                        ->setUser($user)
                        ->setIdfigure($figure)
                        ->setText(implode(" ", $faker->sentences(2)));
                    $manager->persist($comment);
                }
            }
        }
        $manager->flush();
    }
}
