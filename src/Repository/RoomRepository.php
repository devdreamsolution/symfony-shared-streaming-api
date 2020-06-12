<?php

namespace App\Repository;

use App\Entity\Room;
use App\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    private $userRepository;
    private $baseURL;

    public function __construct(UserRepository $userRepository, ManagerRegistry $registry)
    {
        $this->userRepository = $userRepository;
        $this->baseURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        parent::__construct($registry, Room::class);
    }

    /**
     * Transform of all room
     * @param UserRepository $userRepository
     * @return Array[]
     */
    public function transformAll()
    {
        $rooms = $this->findAll();
        $roomsArray = [];

        foreach ($rooms as $room) {
            $roomsArray[] = $this->transform($room);
        }

        return $roomsArray;
    }

    /**
     * Transform of room
     * @param Room $room
     * @return Array[]
     */
    public function transform(Room $room)
    {
        return [
            'id' => $room->getId(),
            'owner' => $this->userRepository->transform($room->getOwner()),
            'name' => $room->getName(),
            'description' => $room->getDescription(),
            'qr_url' => $this->baseURL . '/' . $room->getQrUrl(),
            'start_time' => $room->getStartTime(),
            'created_at' => $room->getCreatedAt(),
            'updated_at' => $room->getUpdatedAt(),
        ];
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
}
