<?php

namespace App\DataFixtures;

use App\Entity\Comments;
use App\Entity\Figure;
use App\Entity\Figuregroup;
use App\Entity\Pictureslink;
use App\Entity\User;
use App\Entity\Videolink;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
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
            'Les grabs',
            'Les rotations',
            'Les flips',
            'Les rotations désaxées',
            'Les slides',
            'Old school'
        ];

        $videosYoutube = [
            'https://www.youtube.com/embed/Zc8Gu8FwZkQ',
            'https://www.youtube.com/embed/0uGETVnkujA',
            'https://www.youtube.com/embed/G9qlTInKbNE',
            'https://www.youtube.com/embed/8AWdZKMTG3U',
            'https://www.youtube.com/embed/SQyTWk7OxSI'
        ];
        $figureDatas = [
          [
              'titre' => 'Mute',
              'desciption' => 'saisie de la carre frontside de la planche entre les deux pieds avec la main avant.',
              'categorie' => 'Les grabs'
                ],
            [
                'titre' => '360',
                'desciption' => 'trois six pour un tour complet.',
                'categorie' => 'Les rotations'
            ],
            [
                'titre' => 'Japan air',
                'desciption' => 'Saisie de l\'avant de la planche, avec la main avant, du côté de la carre frontside.',
                'categorie' => 'Les grabs'
            ],
            [
                'titre' => '1080',
                'desciption' => 'trois tours complets',
                'categorie' => 'Les rotations'
            ],
            [
                'titre' => 'Back flips',
                'desciption' => 'Rotations en arrière',
                'categorie' => 'Les rotations'
            ],
            [
                'titre' => 'Rodeo',
                'desciption' => 'Figure tête en bas où l’athlète pivote en diagonale au-dessus de son épaule pendant qu’il fait un salto',
                'categorie' => 'Les rotations désaxées'
            ],
            [
                'titre' => 'Rocket air',
                'desciption' => 'Figure aérienne où le surfeur saisit la carre pointe du pied à l’avant du pied avant avec la main avant, la jambe est redressée et la planche pointe perpendiculairement au sol',
                'categorie' => 'Old school'
            ],
            [
                'titre' => 'Seat belt',
                'desciption' => 'Figure aérienne où le surfeur saisit 
                le talon de la planche de surf avec sa main avant pendant que la jambe avant est tendue.',
                'categorie' => 'Les grabs'
            ],
            [
                'titre' => 'Truck driver',
                'desciption' => 'saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture)',
                'categorie' => 'Les grabs'
            ],
            [
                'titre' => 'Stalefish',
                'desciption' => ' Figure aérienne où l’athlète saisit la carre côté talons derrière la jambe arrière avec la main arrière pendant que la jambe arrière est redressée.',
                'categorie' => 'Les grabs'
            ]
        ];

        $RandComments = [
            'Trop bien cette figure',
            'C\'est magnifique',
            'C\'est ma figure !',
            'Top',
            'Comment on peut faire ça !? ^^',
            'Polalalalal !',
            'Impressionant !',
            'WTF!',
            'Completement fou :) ',
            'waw',
            'WAOUWWWWW'
        ];

        foreach ($figureGroupeNames as $name) {
            $figuregroupe = new Figuregroup();
            $figuregroupe->setName($name);
            $manager->persist($figuregroupe);
        }

        for ($i = 0; $i < 4; $i++) {
            $user = new User();
            $user->setProfilePicture(Pictureslink::PICTURELINKUSERRAND)
                ->setDatesub($faker->dateTimeInInterval('-30 days', '+5 days'))
                ->setName($faker->firstName())
                ->setMail($faker->safeEmail)
                ->setPassword($this->encoder->encodePassword($user, "testpass"));
                $manager->persist($user);
        }
        /** @var EntityManagerInterface  $manager */
        $manager->flush();

        /** @var User $allUser */
        $allUser = $manager->getRepository(User::class)->findAll();

        /**
         * table containing all user ids to randomize comment creation on line 169
         * @var array $userIds
         */
        $userIds = [];
        foreach ($allUser as $userId) {
            array_push($userIds, $userId->getId());
        }
        foreach ($figureDatas as $figureData) {
            $figure = new  Figure();
            $figure->setUser($user)
                ->setName($figureData['titre'])
                ->setDatecreate($faker->dateTimeInInterval('-30 days', '+5 days'))
                ->setIdfiguregroup(
                    $manager->getRepository(Figuregroup::class)
                        ->findOneBy(['name' => $figureData['categorie']])
                )
                ->setDescription($figureData['desciption'])
                ->setSlug(
                    strtolower(
                        str_replace(
                            ' ',
                            '-',
                            $figureData['titre']
                        )
                    )
                );
            $manager->persist($figure);
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
            for ($l = 0; $l <= 2; $l++) {
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
            for ($n = 0; $n <= 15; $n++) {
                $comment = new Comments();
                $comment->setDateCreate(
                    $faker->dateTimeInInterval('-30 days', '+5 days')
                )
                    ->setUser(
                        $manager->getRepository(User::class)
                            ->findOneBy(['id' => $userIds[array_rand($userIds)]])
                    )
                    ->setFigure($figure)
                    ->setText($RandComments[array_rand($RandComments)]);
                $manager->persist($comment);
            }
        }
        $manager->flush();
    }
}
