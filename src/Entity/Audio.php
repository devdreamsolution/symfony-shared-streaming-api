<?php

namespace App\Entity;

use App\Repository\AudioRepository;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Filesystem\Filesystem;

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
     * @Assert\File(mimeTypes = {"audio/mpeg", "audio/x-wav", "audio/wmv"})
     * @Assert\NotBlank()
     */
    private $audio;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    public function __construct(Room $room, User $recorder, File $audio)
    {
        $this->room = $room;
        $this->recorder = $recorder;
        $this->audio = $audio;
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

    public function getAudio(): ?string
    {
        return $this->audio;
    }

    public function setAudio(File $audio): self
    {
        if ($audio) {
            $uploadDir = 'uploads/audios';
            $fileName = md5(uniqid()) . '.' . $audio->guessExtension();
            $path = $audio->move($uploadDir, $fileName);

            // Old file remove
            $filesystem = new Filesystem();
            $filesystem->remove($this->audio);
            $this->audio = $path;
        }

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
        // audio upload
        if ($this->audio) {
            $uploadDir = 'uploads/audios';
            $fileName = md5(uniqid()) . '.' . $this->audio->guessExtension();
            $path = $this->audio->move($uploadDir, $fileName);
            $this->audio = $path;
        }

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