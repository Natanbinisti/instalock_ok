<?php

namespace App\Repository;

use App\Entity\ReplyComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReplyComment>
 *
 * @method ReplyComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReplyComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReplyComment[]    findAll()
 * @method ReplyComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReplyCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReplyComment::class);
    }

//    /**
//     * @return ReplyComment[] Returns an array of ReplyComment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReplyComment
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}