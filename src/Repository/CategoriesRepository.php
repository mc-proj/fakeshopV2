<?php

namespace App\Repository;

use App\Entity\Categories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Categories|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categories|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categories[]    findAll()
 * @method Categories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categories::class);
    }

    public function findByBegin($debut) {

        return $this->createQueryBuilder('c')
            ->leftJoin("c.sous_categories", "cc")
            ->andWhere('c.nom LIKE :debut')
            ->setParameter('debut', $debut.'%')
            ->select('c.nom as nom_categorie, cc.nom AS nom_categorie_parent')
            ->orderBy('c.nom', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
