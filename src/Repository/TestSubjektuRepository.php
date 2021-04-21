<?php

namespace App\Repository;

use App\Entity\TestSubjektu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TestSubjektu|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestSubjektu|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestSubjektu[]    findAll()
 * @method TestSubjektu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestSubjektuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestSubjektu::class);
    }

    // /**
    //  * @return TestSubjektu[] Returns an array of TestSubjektu objects
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
    public function findOneBySomeField($value): ?TestSubjektu
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
