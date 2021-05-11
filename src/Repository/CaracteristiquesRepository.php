<?php

namespace App\Repository;

use App\Entity\Caracteristiques;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Caracteristiques|null find($id, $lockMode = null, $lockVersion = null)
 * @method Caracteristiques|null findOneBy(array $criteria, array $orderBy = null)
 * @method Caracteristiques[]    findAll()
 * @method Caracteristiques[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CaracteristiquesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Caracteristiques::class);
    }
}
