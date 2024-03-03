<?php

namespace App\Repository;

use App\Entity\RunTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RunTime>
 *
 * @method RunTime|null find($id, $lockMode = null, $lockVersion = null)
 * @method RunTime|null findOneBy(array $criteria, array $orderBy = null)
 * @method RunTime[]    findAll()
 * @method RunTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RunTimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RunTime::class);
    }

    //    /**
    //     * @return RunTime[] Returns an array of RunTime objects
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

    //    public function findOneBySomeField($value): ?RunTime
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
