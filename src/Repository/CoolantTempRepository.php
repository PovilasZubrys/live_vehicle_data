<?php

namespace App\Repository;

use App\Entity\CoolantTemp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CoolantTemp>
 *
 * @method CoolantTemp|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoolantTemp|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoolantTemp[]    findAll()
 * @method CoolantTemp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoolantTempRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoolantTemp::class);
    }

    //    /**
    //     * @return CoolantTemp[] Returns an array of CoolantTemp objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CoolantTemp
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
