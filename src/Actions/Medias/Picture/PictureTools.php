<?php


namespace App\Actions\Medias\Picture;


use App\Entity\Pictureslink;
use App\Repository\PictureslinkRepository;
use Symfony\Component\Filesystem\Filesystem;

trait PictureTools
{
    /** @var string */
    private $tricksPicturesDirectory;
    /** @var PictureslinkRepository */
    private $pictureRepo;
    /** @var Filesystem  */
    private $filesystem;

    /**
     * @param Pictureslink $NewFirstImages
     */
    public function setHeadlineAsFollowImage(Pictureslink $NewFirstImages): void
    {
        $NewFirstImages->setFirstImage(1);
        $this->pictureRepo->pushImage($NewFirstImages);
    }

    /**
     * @param Pictureslink $image
     */
    public function setNewHeadlineImage(Pictureslink $image): void
    {
        $nextImage = $this->pictureRepo->getNextImages($image);
        $this->setHeadlineAsFollowImage($nextImage);
    }

    /**
     * @param Pictureslink $image
     */
    public function removeImageTrickOnServer(Pictureslink $image) :void
    {
        $this->filesystem->remove([
            '',
            '',
            $this->getImagePath($image)
        ]);
    }

    /**
     * @param Pictureslink $image
     * @return string
     */
    public function getImagePath(Pictureslink $image): string
    {
        return $this->tricksPicturesDirectory . $image->getLinkpictures();
    }
}