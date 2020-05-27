<?php

namespace App\Entity;

use App\Repository\AudioRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AudioRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Audio
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="audios")
     * @ORM\JoinColumn(nullable=false)
     */
    private $room;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="my_records")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recorder;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $audio_path;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    public function __construct(Room $room, User $recorder, string $audio_path)
    {
        $this->room = $room;
        $this->recorder = $recorder;
        $this->audio_path = $audio_path;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getRecorder(): ?User
    {
        return $this->recorder;
    }

    public function setRecorder(?User $recorder): self
    {
        $this->recorder = $recorder;

        return $this;
    }

    public function getAudioPath(): ?string
    {
        return $this->audio_path;
    }

    public function setAudioPath(string $audio_path): self
    {
        $this->audio_path = $audio_path;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updated_at = new \DateTime();
    }

}