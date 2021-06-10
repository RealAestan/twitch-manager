<?php

namespace App\Repository;

use App\Entity\Broadcaster;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Broadcaster|null find($id, $lockMode = null, $lockVersion = null)
 * @method Broadcaster|null findOneBy(array $criteria, array $orderBy = null)
 * @method Broadcaster[]    findAll()
 * @method Broadcaster[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BroadcasterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Broadcaster::class);
    }
}
