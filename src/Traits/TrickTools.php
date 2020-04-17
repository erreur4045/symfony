<?php


namespace App\Traits;


use App\Entity\Comments;
use App\Entity\Figure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait TrickTools
{
    /**
     * @param Figure $figure
     * @param array $video
     * @return bool
     */
    protected function isMedias(Figure $figure, array $video): bool
    {
        return empty($this->pictureRepo->findBy(['figure' => $figure->getId(), 'first_image' => 0])) && empty($video);
    }

    /**
     * @param Figure $figure
     * @return bool
     */
    protected function isOneOtherFirstImage(Figure $figure): bool
    {
        return empty($this->pictureRepo->findBy(['figure' => $figure->getId(), 'first_image' => 0]));
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function checkFigureMedias(Request $request): array
    {
        $slug = $request->attributes->get('slug');
        $figure = $this->figureRepo->findOneBy(['slug' => $slug]);
        if (is_null($figure)) {
            throw new NotFoundHttpException('La figure n\'existe pas');
        }

        $video = $this->videoRepo->findBy(['figure' => $figure->getId()]);
        $hasOtherMedia = $this->isMedias($figure, $video);
        $hasOtherPicture = $this->isOneOtherFirstImage($figure);
        return array($figure, $hasOtherMedia, $hasOtherPicture);
    }

    /**
     * @param Figure $figure
     * @return false|float
     */
    protected function getPageMaxCommentFrom(Figure $figure)
    {
        return ceil(count($this->commentRepo->findBy(['figure' => $figure->getId()])) / Comments::LIMIT_PER_PAGE);
    }

    /**
     * @param Figure $figure
     * @return array
     */
    protected function getDataForViewFrom(Figure $figure): array
    {
        $image = $this->pictureRepo->findBy(['figure' => $figure->getId()]);
        $video = $this->videoRepo->findBy(['figure' => $figure->getId()]);
        $hasOtherMedia = empty($this->pictureRepo->findBy([
                'figure' => $figure->getId(),
                'first_image' => 0
            ])) && empty($video);
        $user = $this->tokenStorage->getToken()->getUser();
        return array($image, $video, $hasOtherMedia, $user);
    }
}