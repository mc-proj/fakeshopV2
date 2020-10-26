<?php

namespace App\Repository;

use App\Entity\CodesPromo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CodesPromo|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodesPromo|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodesPromo[]    findAll()
 * @method CodesPromo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodesPromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodesPromo::class);
    }

    // /**
    //  * @return CodesPromo[] Returns an array of CodesPromo objects
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
    public function findOneBySomeField($value): ?CodesPromo
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
