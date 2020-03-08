<?php

namespace App\Services\FormResolvers;

use App\Entity\Figure;
use App\Entity\Pictureslink;
use App\Entity\User;
use App\Entity\Videolink;
use App\Services\Interfaces\FormResolversInterfaces\FormResolverTricksInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class FormResolverTricks extends FormResolver implements FormResolverTricksInterface
{

    /** @var string */
    private $tricksPicturesDirectory;
    /** @var EntityManagerInterface  */
    private $manager;
    /** @var FlashBagInterface  */
    private $bag;
    /** @var FormFactoryInterface  */
    protected $formFactory;

    /**
     * FormResolverTricks constructor.
     * @param string $tricksPicturesDirectory
     * @param EntityManagerInterface $manager
     * @param FlashBagInterface $bag
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        string $tricksPicturesDirectory,
        EntityManagerInterface $manager,
        FlashBagInterface $bag,
        FormFactoryInterface $formFactory
    ) {
        $this->tricksPicturesDirectory = $tricksPicturesDirectory;
        $this->manager = $manager;
        $this->bag = $bag;
        $this->formFactory = $formFactory;
        parent::__construct($formFactory);
    }


    /**
     * @param FormInterface $form
     * @param User $user
     * @throws \Exception
     */
    public function addTrick(FormInterface $form, User $user)
    {
        /** @var  $figure */
        $figure = $form->getData();
        $figure->setUser($user);
        $figure->setDatecreate(new \DateTime('now'));
        if ($figure->getPictureslinks()->count() == 0) {
            $this->addFigureLessPictures($figure);
        } else {
            $this->hasFirstImage($figure);
            $this->setPicturesToFigure($figure);
            $this->addVideosToFigure($figure, Videolink::PATTERNYT);
            $this->manager->persist($figure);
            $this->manager->flush();
            $this->bag->add('success', 'Votre figure a été ajoutée');
        }
    }

    /**
     * @param Figure $figure
     * @throws \Exception
     */
    public function updateTrick(Figure $figure)
    {
        $figure->setDateupdate(new \DateTime('now'));
        $this->manager->persist($figure);
        $this->manager->flush();
        $this->bag->add('success', 'Votre figure a été mise a jour');
    }

    /**
     * @param $figure
     * @param string $patternYT
     * @param $matches
     */
    public function addVideosToFigure($figure, string $patternYT)
    {
        /** @var Videolink $video */
        /** @var  Figure $figure */
        foreach ($figure->getVideolinks() as $video) {
            $videoEmbed = preg_match(
                $patternYT,
                $video->getLinkvideo(),
                $matches
            );
            $linkToStock = 'https://www.youtube.com/embed/' . $matches[1];
            $video->setLinkvideo($linkToStock);
        }
    }

    /**
     * @param $figure
     */
    public function addFigureLessPictures($figure): void
    {
        /** @var Filesystem $filesystem */
        $filesystem = new Filesystem();
        $pictureDefault = new Pictureslink();
        $randId = rand(0, 2);
        $randPicture = Pictureslink::PICTURELINKTRICKRAND[$randId];
        $newPicture = $randPicture . '-' . uniqid() . '.jpg';
        $filesystem->copy(
            $this->tricksPicturesDirectory . $randPicture,
            $this->tricksPicturesDirectory . $newPicture
        );
        $pictureDefault->setFigure($figure)
            ->setLinkpictures($newPicture)
            ->setFirstImage(1)
            ->setAlt('snow');
        if ($figure->getVideolinks()->count() > 0) {
            $this->addVideosToFigure($figure, Videolink::PATTERNYT);
        }
        $this->manager->persist($figure);
        $this->manager->flush();
        $this->manager->persist($pictureDefault);
        $this->manager->flush();
        $this->bag->add('success', 'Votre figure a été ajoutée');
    }

    /**
     * @param $figure
     */
    public function hasFirstImage($figure): void
    {
        /**
         *Ckeck all differents images and check if at least one image
         *is "image_first" otherwise the fist image is changed image_first = true
         *
         * @var array $elements
         */
        /** @var  Figure $figure */
        $elements = $figure->getPictureslinks()->getValues();
        $bool = 0;
        foreach ($elements as $first) {
            if ($first->getFirstImage() == false) {
                $bool = 0;
            } else {
                $bool = 1;
            }
        }
        if ($bool == 0) {
            $elements[0]->setFirstImage(1);
        }
    }

    /**
     * @param $figure
     */
    public function setPicturesToFigure($figure): void
    {
        /** @var Pictureslink $picture */
        /** @var  Figure $figure */
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
            $picture->setLinkpictures($newFilename);
        }
    }
}
