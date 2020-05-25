<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity
 * @UniqueEntity(fields={"email"}, message="I think you are already registered!")
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=500)
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=500)
     * @Assert\NotBlank()
     * Assert\Length(["max" => 100])
     */
    private $roles;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * Assert\Length(["max" => 100])
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * Assert\Length(["max" => 100])
     */
    private $surename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Positive()
     */
    private $age;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\Positive()
     */
    private $vat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * Assert\Length(["max" => 200])
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * Assert\Length(["max" => 200])
     */
    private $city_residence;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Positive()
     */
    private $group_age;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\PositiveOrZero()
     * $gender == 1 : male, $gender == 2 : fermale
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lang = 'en';

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=Room::class, mappedBy="owner", orphanRemoval=true)
     */
    private $my_rooms;

    /**
     * @ORM\ManyToMany(targetEntity=Room::class, mappedBy="users")
     */
    private $other_rooms;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="sender", orphanRemoval=true)
     */
    private $send_messages;

    /**
     * @ORM\ManyToMany(targetEntity=Message::class, mappedBy="receiver")
     */
    private $receive_messages;

    /**
     * @ORM\OneToMany(targetEntity=Audio::class, mappedBy="recorder", orphanRemoval=true)
     */
    private $my_records;

    public function __construct()
    {
        $this->isActive = true;
        $this->my_rooms = new ArrayCollection();
        $this->other_rooms = new ArrayCollection();
        $this->send_messages = new ArrayCollection();
        $this->receive_messages = new ArrayCollection();
        $this->my_records = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles(): array
    {
        $roles[] = $this->roles;

        return array_unique($roles);
    }


    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function setLang(?string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(): self
    {
        $this->created_at = new \DateTime();

        $this->setUpdatedAt();
        
        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(): self
    {
        $this->updated_at = new \DateTime();

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

    /**
     * @return Collection|Message[]
     */
    public function getSendMessages(): Collection
    {
        return $this->send_messages;
    }

    public function addSendMessage(Message $sendMessage): self
    {
        if (!$this->send_messages->contains($sendMessage)) {
            $this->send_messages[] = $sendMessage;
            $sendMessage->setSender($this);
        }

        return $this;
    }

    public function removeSendMessage(Message $sendMessage): self
    {
        if ($this->send_messages->contains($sendMessage)) {
            $this->send_messages->removeElement($sendMessage);
            // set the owning side to null (unless already changed)
            if ($sendMessage->getSender() === $this) {
                $sendMessage->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getReceiveMessages(): Collection
    {
        return $this->receive_messages;
    }

    public function addReceiveMessage(Message $receiveMessage): self
    {
        if (!$this->receive_messages->contains($receiveMessage)) {
            $this->receive_messages[] = $receiveMessage;
            $receiveMessage->addReceiver($this);
        }

        return $this;
    }

    public function removeReceiveMessage(Message $receiveMessage): self
    {
        if ($this->receive_messages->contains($receiveMessage)) {
            $this->receive_messages->removeElement($receiveMessage);
            $receiveMessage->removeReceiver($this);
        }

        return $this;
    }

    /**
     * @return Collection|Audio[]
     */
    public function getMyRecords(): Collection
    {
        return $this->my_records;
    }

    public function addMyRecord(Audio $myRecord): self
    {
        if (!$this->my_records->contains($myRecord)) {
            $this->my_records[] = $myRecord;
            $myRecord->setRecorder($this);
        }

        return $this;
    }

    public function removeMyRecord(Audio $myRecord): self
    {
        if ($this->my_records->contains($myRecord)) {
            $this->my_records->removeElement($myRecord);
            // set the owning side to null (unless already changed)
            if ($myRecord->getRecorder() === $this) {
                $myRecord->setRecorder(null);
            }
        }

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
