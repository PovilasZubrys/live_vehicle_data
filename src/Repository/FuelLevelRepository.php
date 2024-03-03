<?php

namespace App\Repository;

use App\Entity\FuelLevel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FuelLevel>
 *
 * @method FuelLevel|null find($id, $lockMode = null, $lockVersion = null)
 * @method FuelLevel|null findOneBy(array $criteria, array $orderBy = null)
 * @method FuelLevel[]    findAll()
 * @method FuelLevel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FuelLevelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FuelLevel::class);
    }

    //    /**
    //     * @return FuelLevel[] Returns an array of FuelLevel objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?FuelLevel
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
