<?php

namespace App\Repository;

use App\Entity\Figure;
use App\Entity\Pictureslink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pictureslink|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pictureslink|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pictureslink[]    findAll()
 * @method Pictureslink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PictureslinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pictureslink::class);
    }

    public function deletePicture(Pictureslink $picture)
    {
        try {
            $this->_em->remove($picture);
        } catch (ORMException $e) {
        }
        try {
            $this->_em->flush();
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
        }
    }

    public function getNextImages(Pictureslink $pictureslink)
    {
        $figure = $pictureslink->getFigure();
        return $this->findOneBy([
            'figure' => $figure->getId(),
            'first_image' => false
        ]);
    }

    public function pushImage(Pictureslink $image)
    {

        try {
            $this->_em->persist($image);
        } catch (ORMException $e) {
        }
        try {
            $this->_em->flush();
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
        }
    }

    /**
     * @param Figure $figure
     * @return Pictureslink[]
     */
    public function getByTrick(Figure $figure): array
    {
        $figureId = $figure->getId();
        return $this->findBy(['figure' => $figureId]);
    }

    /**
     * @param Figure $figure
     * @return bool
     */
    protected function isOneOtherFirstImage(Figure $figure): bool
    {
        return empty($this->findBy(['figure' => $figure->getId(), 'first_image' => 0]));
    }
}
