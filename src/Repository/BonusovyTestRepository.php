<?php

namespace App\Repository;

use App\Entity\BonusovyTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BonusovyTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method BonusovyTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method BonusovyTest[]    findAll()
 * @method BonusovyTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BonusovyTestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BonusovyTest::class);
    }

    // /**
    //  * @return BonusovyTest[] Returns an array of BonusovyTest objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BonusovyTest
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
