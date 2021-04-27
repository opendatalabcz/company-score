<?php

namespace App\Repository;


use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

//    private $entityManager;
//
//    private $objectRepository;
//
//    public function __construct(EntityManagerInterface $entityManager)
//    {
//        $this->entityManager = $entityManager;
//        $this->objectRepository = $this->entityManager->getRepository(Account::class);
//    }
//
//    public function findById(int $id): ?Account
//    {
//        return $this->entityManager->find(Account::class, $id);
//    }
//
//    public function findOneByUserName(string $username): ?Account
//    {
//        try {
//            return $this->entityManager->createQueryBuilder()
//                ->select('a')
//                ->from('Account', 'a')
//                ->andWhere('a.username = :username')
//                ->setParameter('username', $$username)
//                ->getQuery()
//                ->getOneOrNullResult();
//        } catch (NonUniqueResultException $e) {
//            return null;
//        }
//    }
//
//    public function save(Account $account): void
//    {
//        $this->entityManager->persist($account);
//        $this->entityManager->flush();
//    }
//
//    public function delete(Account $account): void
//    {
//        $this->entityManager->remove($account);
//        $this->entityManager->flush();
//    }
//
//    public function findAll(): array
//    {
//        $this->objectRepository->findAll();
//    }
    /**
     * Loads the user for the given username.
     *
     * This method must return null if the user is not found.
     *
     * @param string $username The username
     *
     * @return UserInterface|null
     */
    public function loadUserByUsername($username)
    {
        return $this->createQuery(
            'SELECT u
                FROM App\Entity\User u
                WHERE u.username = :query'
        )
            ->setParameter('query', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
