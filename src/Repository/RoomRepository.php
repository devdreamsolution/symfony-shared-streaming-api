<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    // /**
    //  * @return Room[] Returns an array of Room objects
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
    public function findOneBySomeField($value): ?Room
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Get room data
     * @param Room
     * @return array[]
     */
    public function transform(Room $room)
    {
        return [
            'id' => $room->getId(),
            'owner' => $this->getOwner($room->getOwner()),
            'name' => $room->getName(),
            'description' => $room->getDescription(),
            'qr_url' => $room->getQrUrl(),
            'start_time' => $room->getStartTime(),
        ];
    }

    /**
     * All rooms list
     * @return array[]
     */
    public function transformAll()
    {
        $rooms = $this->findAll();
        $roomsArray = [];

        foreach($rooms as $room)
        {
            $roomsArray[] = $this->transform($room);
        }

        return $roomsArray;
    }

    /**
     * Get owner data of room
     * @param User
     * @return array[]
     */
    public function getOwner(User $user)
    {
        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'surename' => $user->getSurename(),
            'roles' => $user->getRoles(),
        ];
    }
}
