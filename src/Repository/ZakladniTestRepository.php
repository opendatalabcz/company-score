<?php

namespace App\Repository;

use App\Entity\ZakladniTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ZakladniTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ZakladniTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ZakladniTest[]    findAll()
 * @method ZakladniTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZakladniTestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ZakladniTest::class);
    }

    // /**
    //  * @return ZakladniTest[] Returns an array of ZakladniTest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('z.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ZakladniTest
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
