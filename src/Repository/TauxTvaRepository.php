<?php

namespace App\Repository;

use App\Entity\TauxTva;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TauxTva|null find($id, $lockMode = null, $lockVersion = null)
 * @method TauxTva|null findOneBy(array $criteria, array $orderBy = null)
 * @method TauxTva[]    findAll()
 * @method TauxTva[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TauxTvaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TauxTva::class);
    }

    // /**
    //  * @return TauxTva[] Returns an array of TauxTva objects
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
    public function findOneBySomeField($value): ?TauxTva
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
