<?php

namespace App\Repository;

use App\Entity\Speed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Speed>
 *
 * @method Speed|null find($id, $lockMode = null, $lockVersion = null)
 * @method Speed|null findOneBy(array $criteria, array $orderBy = null)
 * @method Speed[]    findAll()
 * @method Speed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpeedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Speed::class);
    }

//    /**
//     * @return Speed[] Returns an array of Speed objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Speed
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
