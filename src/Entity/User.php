<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"email"}, message="I think you are already registered!")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $surename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Image()
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
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city_residence;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Positive()
     */
    private $group_age;

    /**
     * Gender 0: none, 1: male, 2: fermale
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\PositiveOrZero()
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
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
     * @ORM\OneToMany(targetEntity=Room::class, mappedBy="owner")
     */
    private $my_rooms;

    /**
     * @ORM\ManyToMany(targetEntity=Room::class, mappedBy="user")
     */
    private $other_rooms;

    /**
     * @ORM\OneToMany(targetEntity=Audio::class, mappedBy="recorder")
     */
    private $my_audios;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="sender")
     */
    private $send_messages;

    /**
     * @ORM\ManyToMany(targetEntity=Message::class, mappedBy="receiver")
     */
    private $receive_messages;

    public function __construct(string $email, $name, $surename, array $roles, string $lang = 'en', $city_residence = null, $address = null, int $group_age = null, $gender = null, $age = null, float $vat = null, File $picture = null)
    {
        $this->email = $email;
        $this->name = $name;
        $this->surename = $surename;
        $this->roles = $roles;
        $this->lang = $lang;
        $this->city_residence = $city_residence;
        $this->group_age = $group_age;
        $this->gender = $gender;
        $this->age = $age;
        $this->vat = $vat;
        $this->address = $address;
        $this->picture = $picture;

        $this->my_rooms = new ArrayCollection();
        $this->other_rooms = new ArrayCollection();
        $this->my_audios = new ArrayCollection();
        $this->send_messages = new ArrayCollection();
        $this->contents = new ArrayCollection();
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
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
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
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

    public function setPicture(?File $picture = null): self
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

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        // picture upload
        if ($this->picture) {
            $uploadDir = 'uploads\pictures';
            $fileName = md5(uniqid()) . '.' . $this->picture->guessExtension();
            $path = $this->picture->move($uploadDir, $fileName);
            $this->picture = $path;
        } else {
            $this->picture = 'assets\images\default-avatar.jpg';
        }

        // created at
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        // picture upload
        if ($this->picture) {
            $uploadDir = 'uploads\pictures';
            $fileName = md5(uniqid()) . '.' . $this->picture->guessExtension();
            $path = $this->picture->move($uploadDir, $fileName);
            $this->picture = $path;
        } else {
            $this->picture = 'assets\images\default-avatar.jpg';
        }

        // updated at
        $this->updated_at = new \DateTime();
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
     * @return Collection|Audio[]
     */
    public function getMyAudios(): Collection
    {
        return $this->my_audios;
    }

    public function addMyAudio(Audio $myAudio): self
    {
        if (!$this->my_audios->contains($myAudio)) {
            $this->my_audios[] = $myAudio;
            $myAudio->setRecorder($this);
        }

        return $this;
    }

    public function removeMyAudio(Audio $myAudio): self
    {
        if ($this->my_audios->contains($myAudio)) {
            $this->my_audios->removeElement($myAudio);
            // set the owning side to null (unless already changed)
            if ($myAudio->getRecorder() === $this) {
                $myAudio->setRecorder(null);
            }
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

    public function addReceiveMessage(Message $receive_message): self
    {
        if (!$this->receive_messages->contains($receive_message)) {
            $this->receive_messages[] = $receive_message;
            $receive_message->addReceiver($this);
        }

        return $this;
    }

    public function removeReceiveMessages(Message $receive_message): self
    {
        if ($this->receive_messages->contains($receive_message)) {
            $this->receive_messages->removeElement($receive_message);
            $receive_message->removeReceiver($this);
        }

        return $this;
    }
}
