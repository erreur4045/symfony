<?php

namespace App\Repository;

use App\Entity\Comments;
use App\Entity\Figure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comments::class);
    }

    public function deleteComment(Comments $comment)
    {
        try {
            $this->_em->remove($comment);
        } catch (ORMException $e) {
        }
        try {
            $this->_em->flush();
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
        }
    }

    /**
     * @return int|void
     */
    public function countCommentsByIdTrick($idFigure)
    {
        return count($this->findBy(['figure' => $idFigure ]));
    }

    /**
     * @param Request $request
     * @return Comments
     */
    public function getCommentFrom(Request $request)
    {
        $commentId = $request->get('id');
        ;
        return $this->findOneBy(['id' => $commentId]);
    }

    /**
     * @param $trickId
     * @param $offset
     * @return Comments[]
     */
    public function getCommentsToShow($trickId, $offset): array
    {
        return $this->findBy(
            ['figure' => $trickId],
            [],
            Comments::LIMIT_PER_PAGE,
            $offset
        );
    }

    /**
     * @param Figure $figure
     * @return Comments[]
     */
    public function getByTrick(Figure $figure)
    {
        return $this->findBy(['figure' => $figure->getId()], [], Comments::LIMIT_PER_PAGE, null);
    }
}
