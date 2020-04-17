<?php

namespace App\Repository;

use App\Entity\Figure;
use App\Entity\Videolink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Videolink|null find($id, $lockMode = null, $lockVersion = null)
 * @method Videolink|null findOneBy(array $criteria, array $orderBy = null)
 * @method Videolink[]    findAll()
 * @method Videolink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideolinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Videolink::class);
    }

    public function removeVideo(?Videolink $video)
    {
        $this->_em->remove($video);
        $this->_em->flush();
    }

    /**
     * @param Figure $figure
     * @return Videolink[]
     */
    public function getByTrick(Figure $figure) :array
    {
        return $this->findBy(['figure' => $figure->getId()]);
    }
}
