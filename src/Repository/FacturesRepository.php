<?php

namespace App\Repository;

use App\Entity\FacturesSP;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FacturesSP|null find($id, $lockMode = null, $lockVersion = null)
 * @method FacturesSP|null findOneBy(array $criteria, array $orderBy = null)
 * @method FacturesSP[]    findAll()
 * @method FacturesSP[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacturesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FacturesSP::class);
    }

    public function recupereMinimumInfos($user_id) {

        return $this->createQueryBuilder('f')
            ->where('f.users_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->select('f.id as id_facture, f.date_creation AS date, f.montant_total AS montant')
            ->getQuery()
            ->getResult();
    }
}
