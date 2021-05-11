<?php

namespace App\Repository;

use App\Entity\AdressesLivraison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdressesLivraison|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdressesLivraison|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdressesLivraison[]    findAll()
 * @method AdressesLivraison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdressesLivraisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdressesLivraison::class);
    }
}
