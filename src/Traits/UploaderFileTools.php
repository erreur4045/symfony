<?php


namespace App\Traits;


use App\Entity\Pictureslink;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;

trait UploaderFileTools
{

    /**
     * @param Pictureslink $uploadedFile
     * @return string|string[]
     */
    protected function getFilename(Pictureslink $uploadedFile)
    {
        $picture = $uploadedFile->getPicture();
        return pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
    }

    /**
     * @param $originalFilename
     * @return false|string
     */
    protected function checkFilenameAccordingASCIILatin($originalFilename)
    {
        return transliterator_transliterate(
            'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
            $originalFilename
        );
    }

    /**
     * @param string $safeFilename
     * @param Pictureslink $uploadedFile
     * @return string
     */
    protected function setUniqueFileName(string $safeFilename, Pictureslink $uploadedFile): string
    {
        $picture = $uploadedFile->getPicture();
        return $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();
    }

    /**
     * @param Pictureslink $uploadedFile
     * @param string $newFilename
     * @return File
     */
    protected function moveFileOnPath(Pictureslink $uploadedFile, string $newFilename): File
    {
        $picture = $uploadedFile->getPicture();
        return $picture->move(
            $this->tricksPicturesDirectory,
            $newFilename
        );
    }

    /**
     * @param FormInterface $form
     * @param string $formField
     * @return mixed
     */
    protected function getDataFromField(FormInterface $form, string $formField)
    {
        return $form[$formField]->getData();
    }

    /**
     * @param Pictureslink $expiredPicture
     * @param Pictureslink $newPicture
     */
    protected function setIsFirstImage(Pictureslink $expiredPicture, Pictureslink $newPicture): void
    {
        if ($expiredPicture->getFirstImage() == true)
            $newPicture->setFirstImage(true);
    }

    /**
     * @param Pictureslink $expiredPicture
     */
    protected function removeExpireFile(Pictureslink $expiredPicture): void
    {
        $this->removeImageTrickOnServer($expiredPicture);
        $this->removeFromDataBase($expiredPicture);
    }

    /**
     * @param Pictureslink $uploadedFile
     * @return string
     */
    protected function assignValidName(Pictureslink $uploadedFile): string
    {
        $originalFilename = $this->getFilename($uploadedFile);
        $safeFilename = $this->checkFilenameAccordingASCIILatin($originalFilename);
        return $this->setUniqueFileName($safeFilename, $uploadedFile);
    }
}