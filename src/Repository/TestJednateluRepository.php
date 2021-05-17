<?php

namespace App\Repository;

use App\Entity\TestJednatelu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TestJednatelu|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestJednatelu|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestJednatelu[]    findAll()
 * @method TestJednatelu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestJednateluRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestJednatelu::class);
    }

    // /**
    //  * @return TestJednatelu[] Returns an array of TestJednatelu objects
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
    public function findOneBySomeField($value): ?TestJednatelu
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
