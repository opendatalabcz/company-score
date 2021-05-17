<?php

namespace App\Repository;

use App\Entity\TestDomeny;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TestDomeny|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestDomeny|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestDomeny[]    findAll()
 * @method TestDomeny[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestDomenyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestDomeny::class);
    }

    // /**
    //  * @return TestDomeny[] Returns an array of TestDomeny objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TestDomeny
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
