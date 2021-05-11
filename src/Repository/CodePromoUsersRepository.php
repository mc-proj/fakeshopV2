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
}
