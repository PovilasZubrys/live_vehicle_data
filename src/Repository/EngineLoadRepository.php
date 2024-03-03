<?php

namespace App\Repository;

use App\Entity\EngineLoad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EngineLoad>
 *
 * @method EngineLoad|null find($id, $lockMode = null, $lockVersion = null)
 * @method EngineLoad|null findOneBy(array $criteria, array $orderBy = null)
 * @method EngineLoad[]    findAll()
 * @method EngineLoad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EngineLoadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EngineLoad::class);
    }

    //    /**
    //     * @return EngineLoad[] Returns an array of EngineLoad objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?EngineLoad
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
