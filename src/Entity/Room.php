<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RoomRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Room
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="my_rooms")
     */
    private $owner;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="other_rooms")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $qr_url;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start_time;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=Audio::class, mappedBy="room", orphanRemoval=true)
     */
    private $audios;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="room", orphanRemoval=true)
     */
    private $messages;

    public function __construct(User $owner, string $name, string $description, string $qr_url, DateTime $start_time)
    {
        $this->user = new ArrayCollection();

        $this->owner = $owner;
        $this->name = $name;
        $this->description = $description;
        $this->qr_url = $qr_url;
        $this->start_time = $start_time;
        $this->adduser($owner);

        $this->audios = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getQrUrl(): ?string
    {
        return $this->qr_url;
    }

    public function setQrUrl(string $qr_url): self
    {
        $this->qr_url = $qr_url;

        return $this;
    }

    public function getStartTime(): ?\DateTime
    {
        return $this->start_time;
    }

    public function setStartTime(\DateTime $start_time): self
    {
        $this->start_time = $start_time;

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

    /**
     * @ORM\PreRemove
     */
    // public function preRemove()
    // {
    //     $audios = $this->getAudios();

    //     foreach($audios as $audio)
    //     {
    //         $this->removeAudio($audio);
    //     }
    // }

    /**
     * @return Collection|Audio[]
     */
    public function getAudios(): Collection
    {
        return $this->audios;
    }

    public function addAudio(Audio $audio): self
    {
        if (!$this->audios->contains($audio)) {
            $this->audios[] = $audio;
            $audio->setRoom($this);
        }

        return $this;
    }

    public function removeAudio(Audio $audio): self
    {
        if ($this->audios->contains($audio)) {
            $this->audios->removeElement($audio);
            // set the owning side to null (unless already changed)
            if ($audio->getRoom() === $this) {
                $audio->setRoom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setRoom($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getRoom() === $this) {
                $message->setRoom(null);
            }
        }

        return $this;
    }
}
