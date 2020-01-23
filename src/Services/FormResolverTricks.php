<?php

namespace App\Services;

use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FormResolverTricks extends FormResolver
{
    /**
     *
     *
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    /**
     *
     *
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     *
     *
     * @var FlashBagInterface
     */
    private $bag;

    /**
     *
     *
     * @var UploaderPicture
     */
    private $uploaderPicture;

    /**
     *
     *
     * @var string
     */
    private $tricksPicturesDirectory;

    public function __construct(
        UserPasswordEncoderInterface $encoder,
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        FormFactoryInterface $formFactory,
        string $tricksPicturesDirectory,
        UploaderPicture $uploaderPicture
    ) {
        parent::__construct($formFactory);
        $this->encoder = $encoder;
        $this->bag = $bag;
        $this->manager = $manager;
        $this->uploaderPicture = $uploaderPicture;
        $this->tricksPicturesDirectory = $tricksPicturesDirectory;
    }

    public function addTrick(FormInterface $form, User $user)
    {
        /**
*
         *
 * @var Figure $figure
*/
        $figure = $form->getData();
        $figure->setUser($user);
        $figure->setDatecreate(new \DateTime('now'));

        if (is_null($figure->getPictureslinks()->get('elements'))) {
            $pictureDefault = new Pictureslink();
            $randId = rand(0, 2);
            $randPicture = Pictureslink::PICTURELINKRAND[$randId];
            $pictureDefault->setFigure($figure)
                ->setUser($user)
                ->setLinkpictures($randPicture)
                ->setFirstImage(1);
            $this->manager->persist($figure);
            $this->manager->flush();
            $this->manager->persist($pictureDefault);
            $this->manager->flush();
        }
        foreach ($figure->getPictureslinks() as $picture) {
            /**
*
             *
 * @var UploadedFile $nameImage
*/
            $nameImage = $picture->getPicture();
            $originalName = $nameImage->getClientOriginalName();
            $safeFilename = transliterator_transliterate(
                'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
                $originalName
            );
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $nameImage->guessExtension();
            try {
                $nameImage->move(
                    $this->tricksPicturesDirectory,
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $picture->setLinkpictures($newFilename)
                ->setUser($user);
        }
        foreach ($figure->getVideolinks() as $video) {
            $videoEmbed = preg_match(
                '/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((?:\w|-){11})(?:&list=(\S+))?$/',
                $video->getLinkvideo(),
                $matches
            );
            $linkToStock = 'https://www.youtube.com/embed/' . $matches[1];
            $video->setLinkvideo($linkToStock);
        }
        $this->manager->persist($figure);
        $this->manager->flush();
        $this->bag->add('success', 'Votre figure a été ajouter');
    }

    public function updateTrick(Figure $figure)
    {
        $figure->setDateupdate(new \DateTime('now'));
        $this->manager->persist($figure);
        $this->manager->flush();
        $this->bag->add('success', 'Votre figure a été mise a jour');
    }
}
