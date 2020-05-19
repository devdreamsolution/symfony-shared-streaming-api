<?php

namespace App\Repository;

use App\Entity\RoomUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RoomUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomUser[]    findAll()
 * @method RoomUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomUser::class);
    }

    // /**
    //  * @return RoomUser[] Returns an array of RoomUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RoomUser
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
