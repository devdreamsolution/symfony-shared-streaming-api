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
     * $user_type == 0 : turist, $user_type == 1 : guide
     */
    private $user_type = 0;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="sender", orphanRemoval=true)
     */
    private $sent_messages;

    /**
     * @ORM\ManyToMany(targetEntity=Message::class, mappedBy="receiver")
     */
    private $received_messages;

    /**
     * @ORM\OneToMany(targetEntity=Room::class, mappedBy="owner", orphanRemoval=true)
     */
    private $my_rooms;

    /**
     * @ORM\ManyToMany(targetEntity=Room::class, mappedBy="user")
     */
    private $other_rooms;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $age;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city_residence;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $group_age;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lang;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=Audio::class, mappedBy="recorder", orphanRemoval=true)
     */
    private $audios;

    public function __construct()
    {
        $this->sent_messages = new ArrayCollection();
        $this->received_messages = new ArrayCollection();
        $this->my_rooms = new ArrayCollection();
        $this->other_rooms = new ArrayCollection();
        $this->audios = new ArrayCollection();
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

    /**
     * @return Collection|Room[]
     */
    public function getMyRooms(): Collection
    {
        return $this->my_rooms;
    }

    public function addMyRoom(Room $myRoom): self
    {
        if (!$this->my_rooms->contains($myRoom)) {
            $this->my_rooms[] = $myRoom;
            $myRoom->setOwner($this);
        }

        return $this;
    }

    public function removeMyRoom(Room $myRoom): self
    {
        if ($this->my_rooms->contains($myRoom)) {
            $this->my_rooms->removeElement($myRoom);
            // set the owning side to null (unless already changed)
            if ($myRoom->getOwner() === $this) {
                $myRoom->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Room[]
     */
    public function getOtherRooms(): Collection
    {
        return $this->other_rooms;
    }

    public function addOtherRoom(Room $otherRoom): self
    {
        if (!$this->other_rooms->contains($otherRoom)) {
            $this->other_rooms[] = $otherRoom;
            $otherRoom->addUser($this);
        }

        return $this;
    }

    public function removeOtherRoom(Room $otherRoom): self
    {
        if ($this->other_rooms->contains($otherRoom)) {
            $this->other_rooms->removeElement($otherRoom);
            $otherRoom->removeUser($this);
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function getSurename(): ?string
    {
        return $this->surename;
    }

    public function setSurename(string $surename): self
    {
        $this->surename = $surename;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getVat(): ?float
    {
        return $this->vat;
    }

    public function setVat(?float $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCityResidence(): ?string
    {
        return $this->city_residence;
    }

    public function setCityResidence(?string $city_residence): self
    {
        $this->city_residence = $city_residence;

        return $this;
    }

    public function getGroupAge(): ?int
    {
        return $this->group_age;
    }

    public function setGroupAge(?int $group_age): self
    {
        $this->group_age = $group_age;

        return $this;
    }

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender(?int $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(): self
    {
        $this->created_at = new \DateTime();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(): self
    {
        $this->updated_at = new \DateTime();

        return $this;
    }

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
            $audio->setRecorder($this);
        }

        return $this;
    }

    public function removeAudio(Audio $audio): self
    {
        if ($this->audios->contains($audio)) {
            $this->audios->removeElement($audio);
            // set the owning side to null (unless already changed)
            if ($audio->getRecorder() === $this) {
                $audio->setRecorder(null);
            }
        }

        return $this;
    }
}
