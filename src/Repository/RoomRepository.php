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

    public function __construct(UserRepository $userRepository, ManagerRegistry $registry)
    {
        $this->userRepository = $userRepository;
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
            'qr_code' => $room->getQrCode(),
            'start_time' => $room->getStartTime(),
            'created_at' => $room->getCreatedAt(),
            'updated_at' => $room->getUpdatedAt(),
        ];
    }

    /**
     * Transform by QR code
     * @param String $qr_code
     * @return Array[]
     */
    public function transformByQrCode(String $qr_code)
    {
        $room = $this->findOneByQrCode($qr_code);
        if ($room) {
            $result['success'] = true;
            $result['message'] = '';
            $result['data'] = $this->transform($room);
        } else {
            $result['success'] = false;
            $result['message'] = "Room record with QR code: $qr_code not found.";
            $result['data'] = null;
        }

        return $result;
    }

    /**
     * Find one by QR code
     * @param String $qr_code
     * @return Room
     */
    public function findOneByQrCode(String $qr_code): ?Room
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.qr_code = :qr_code')
            ->setParameter('qr_code', $qr_code)
            ->getQuery()
            ->getOneOrNullResult()
        ;
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
}
