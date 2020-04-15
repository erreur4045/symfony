<?php

namespace App\Repository;

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
    // /**
    //  * @return Videolink[] Returns an array of Videolink objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Videolink
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
