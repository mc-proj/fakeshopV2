<?php

namespace App\Repository;

use App\Entity\FacturesProduits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FacturesProduits|null find($id, $lockMode = null, $lockVersion = null)
 * @method FacturesProduits|null findOneBy(array $criteria, array $orderBy = null)
 * @method FacturesProduits[]    findAll()
 * @method FacturesProduits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacturesProduitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FacturesProduits::class);
    }
}
