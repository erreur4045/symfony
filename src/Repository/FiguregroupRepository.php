<?php

namespace App\Repository;

use App\Entity\Figuregroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Figuregroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method Figuregroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method Figuregroup[]    findAll()
 * @method Figuregroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FiguregroupRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Figuregroup::class);
    }

    // /**
    //  * @return Figuregroup[] Returns an array of Figuregroup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Figuregroup
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
