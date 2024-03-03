<?php

namespace App\Repository;

use App\Entity\OilTemp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OilTemp>
 *
 * @method OilTemp|null find($id, $lockMode = null, $lockVersion = null)
 * @method OilTemp|null findOneBy(array $criteria, array $orderBy = null)
 * @method OilTemp[]    findAll()
 * @method OilTemp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OilTempRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OilTemp::class);
    }

    //    /**
    //     * @return OilTemp[] Returns an array of OilTemp objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?OilTemp
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
