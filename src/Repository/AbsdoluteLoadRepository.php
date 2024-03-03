<?php

namespace App\Repository;

use App\Entity\AbsdoluteLoad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AbsdoluteLoad>
 *
 * @method AbsdoluteLoad|null find($id, $lockMode = null, $lockVersion = null)
 * @method AbsdoluteLoad|null findOneBy(array $criteria, array $orderBy = null)
 * @method AbsdoluteLoad[]    findAll()
 * @method AbsdoluteLoad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbsdoluteLoadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbsdoluteLoad::class);
    }

    //    /**
    //     * @return AbsdoluteLoad[] Returns an array of AbsdoluteLoad objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AbsdoluteLoad
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
