<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sent_messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sender;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="received_messages")
     */
    private $receiver;

    public function __construct()
    {
        $this->receiver = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getReceiver(): Collection
    {
        return $this->receiver;
    }

    public function addReceiver(User $receiver): self
    {
        if (!$this->receiver->contains($receiver)) {
            $this->receiver[] = $receiver;
        }

        return $this;
    }

    public function removeReceiver(User $receiver): self
    {
        if ($this->receiver->contains($receiver)) {
            $this->receiver->removeElement($receiver);
        }

        return $this;
    }
}
