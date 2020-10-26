<?php

namespace App\Repository;

use App\Entity\CodePromoUsers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CodePromoUsers|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodePromoUsers|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodePromoUsers[]    findAll()
 * @method CodePromoUsers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodePromoUsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodePromoUsers::class);
    }

    // /**
    //  * @return CodePromoUsers[] Returns an array of CodePromoUsers objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CodePromoUsers
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
