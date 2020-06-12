<?php

namespace App\Repository;

use App\Entity\Audio;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Audio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Audio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Audio[]    findAll()
 * @method Audio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AudioRepository extends ServiceEntityRepository
{
    private $roomRepository;
    private $userRepository;
    private $baseURL;

    public function __construct(RoomRepository $roomRepository, UserRepository $userRepository, ManagerRegistry $registry)
    {
        $this->roomRepository = $roomRepository;
        $this->userRepository = $userRepository;
        $this->baseURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

        parent::__construct($registry, Audio::class);
    }

    /**
     * Transform of all audio
     * @return Array[]
     */
    public function transformAll()
    {
        $audios = $this->findAll();
        $audioArray = [];

        foreach ($audios as $audio) {
            $audioArray[] = $this->transform($audio);
        }

        return $audioArray;
    }

    /**
     * Transform by room id
     * @param int $room_id
     * @return Array[]
     */
    public function transformByRoom(int $room_id)
    {
        $audios = $this->findByRoom($room_id);
        $audioArray = [];

        foreach ($audios as $audio) {
            $audioArray[] = $this->transform($audio);
        }

        return $audioArray;
    }

    /**
     * Transform of audio
     * @param Audio $audio
     * @return Array[]
     */
    public function transform(Audio $audio)
    {
        return [
            'id' => $audio->getId(),
            'room' => $this->roomRepository->transform($audio->getRoom()),
            'recorder' => $this->userRepository->transform($audio->getRecorder()),
            'audio' => $this->baseURL . '/' . $audio->getAudio(),
            'created_at' => $audio->getCreatedAt(),
            'updated_at' => $audio->getUpdatedAt(),
        ];
    }

    /**
     * Find by room id
     * @param int $room_id
     * @return Audio[] Returns an array of Audio objects
     */
    public function findByRoom(int $room_id)
    {
        return $this->createQueryBuilder('audio')
            ->andWhere('audio.room = :val')
            ->setParameter('val', $room_id)
            ->orderBy('audio.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Audio
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
