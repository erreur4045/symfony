<?php

namespace App\Repository;

use App\Entity\Figure;
use App\Traits\HomeTools;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Figure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Figure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Figure[]    findAll()
 * @method Figure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FigureRepository extends ServiceEntityRepository
{
    use HomeTools;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Figure::class);
    }

    /**
     * @param $slug
     * @return Figure
     */
    public function getTrickFromSlug($slug) :Figure
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    /**
     * @param $user
     * @return Figure[]
     */
    public function getTricksFromUser($user): array
    {
        return $this->findBy(['user' => $user]);
    }

    /**
     * @return Figure[]
     */
    public function getFirstPageOfTricks(): array
    {
        return $this->findBy([], [], Figure::LIMIT_PER_PAGE, null);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getTricksToDisplay(Request $request): array
    {
        $pageId = $request->query->get('page');
        return $this->findBy(
            [],
            [],
            Figure::LIMIT_PER_PAGE,
            $this->computeOffset($pageId)
        );
    }

    /**
     * @return int
     */
    public function countTricks(): int
    {
        return $this->count([]);
    }
}
