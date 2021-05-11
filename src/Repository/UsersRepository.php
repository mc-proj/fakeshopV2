<?php

namespace App\Repository;

use App\Entity\UsersSP;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UsersSP|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersSP|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersSP[]    findAll()
 * @method UsersSP[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersSP::class);
    }
}
