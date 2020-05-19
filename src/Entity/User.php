<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $user_type;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="sender", orphanRemoval=true)
     */
    private $sent_messages;

    /**
     * @ORM\ManyToMany(targetEntity=Message::class, mappedBy="receiver")
     */
    private $received_messages;

    public function __construct()
    {
        $this->sent_messages = new ArrayCollection();
        $this->received_messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserType(): ?int
    {
        return $this->user_type;
    }

    public function setUserType(int $user_type): self
    {
        $this->user_type = $user_type;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getSentMessages(): Collection
    {
        return $this->sent_messages;
    }

    public function addSentMessage(Message $sentMessage): self
    {
        if (!$this->sent_messages->contains($sentMessage)) {
            $this->sent_messages[] = $sentMessage;
            $sentMessage->setSender($this);
        }

        return $this;
    }

    public function removeSentMessage(Message $sentMessage): self
    {
        if ($this->sent_messages->contains($sentMessage)) {
            $this->sent_messages->removeElement($sentMessage);
            // set the owning side to null (unless already changed)
            if ($sentMessage->getSender() === $this) {
                $sentMessage->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getReceivedMessages(): Collection
    {
        return $this->received_messages;
    }

    public function addReceivedMessage(Message $receivedMessage): self
    {
        if (!$this->received_messages->contains($receivedMessage)) {
            $this->received_messages[] = $receivedMessage;
            $receivedMessage->addReceiver($this);
        }

        return $this;
    }

    public function removeReceivedMessage(Message $receivedMessage): self
    {
        if ($this->received_messages->contains($receivedMessage)) {
            $this->received_messages->removeElement($receivedMessage);
            $receivedMessage->removeReceiver($this);
        }

        return $this;
    }
}
