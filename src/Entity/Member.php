<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MemberRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MemberRepository::class)
 * @ApiResource
 */
class Member
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @Assert\NotBlank (message="Veuillez renseigner le prénom de l'adhérent")
     * @Assert\Length(
     *      min = 2,
     *      max = 55,
     *      minMessage = "Le prénom ne doit pas faire moins de {{ limit }} caractères",
     *      maxMessage = "le prénom ne doit pas dépasser {{ limit }} caractères"
     * )
     * @Assert\Regex ("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u", message="impossible d'utiliser des caractères spéciaux")
     * @ORM\Column(type="string", length=55)
     */
    private ?string $firstName;

    /**
     * @Assert\NotBlank (message="Veuillez renseigner le nom de l'adhérent")
     * @Assert\Length(
     *      min = 2,
     *      max = 55,
     *      minMessage = "Le nom ne doit pas faire moins de {{ limit }} caractères",
     *      maxMessage = "le nom ne doit pas dépasser {{ limit }} caractères"
     * )
     * @Assert\Regex("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u", message="Impossible d'utiliser des caractères spéciaux")
     * @ORM\Column(type="string", length=55)
     */
    private string $lastName;

    /**
     * @Assert\NotBlank (message="Veuillez renseigner le sexe de l'adhérent")
     * @Assert\Choice({"Homme", "Femme"}, message="Erreur sur le sexe de l'adhérent")
     * @ORM\Column(type="string", length=10)
     */
    private ?string $sex;

    /**
     * @Assert\Type("\DateTimeInterface")
     * @Assert\NotBlank (message="Veuillez renseigner la date de naissance de l'adhérent")
     * @Assert\LessThan("today")
     * @ORM\Column(type="date")
     */
    private ?DateTimeInterface $birthdate;

    /**
     * @Assert\Email(message="Veuillez renseigner un email valide")
     * @Assert\NotBlank (message="Veuillez renseigner un email pour l'adhérent")
     * @ORM\Column(type="string", length=255)
     */
    private ?string $email;

    /**
     * @Assert\NotBlank (message="Veuillez renseigner l'adresse de l'adhérent")
     * @ORM\Column(type="string", length=255)
     */
    private ?string $streetAddress;

    /**
     * @Assert\NotBlank (message="Veuillez renseigner le code postal de l'adhérent")
     * @ORM\Column(type="string", length=55)
     */
    private ?string $postalCode;

    /**
     * @Assert\NotBlank (message="Veuillez renseigner la ville de l'adhérent")
     * @ORM\Column(type="string", length=100)
     */
    private ?string $city;

    /**
     * @Assert\NotBlank (message="Veuillez renseigner la nationalité de l'adhérent")
     * @ORM\Column(type="string", length=100)
     */
    private ?string $nationality;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $comment;

    /**
     * @Assert\Choice({"aucun","14e keup", "13e keup", "12e keup", "11e keup", "10e keup", "9e keup", "8e keup","7e keup","6e keup","5e keup","4e keup","3e keup","2e keup","1er keup","BanDan","1er Dan/Poom","2e Dan/Poom","3e Dan/Poom","4e Dan","5e Dan","6e Dan","7e Dan", "8e Dan", "9e Dan"}, message="La valeur du niveau n'est pas correcte")
     * @Assert\NotBlank (message="Veuillez renseigner le niveau de l'adhérent")
     * @ORM\Column(type="string", length=55, nullable=true)
     */
    private ?string $level;

    /**
     * @ORM\Column(type="string", length=55, nullable=true)
     */
    private ?string $emergencyPhone;

    /**
     * @Assert\Choice({"Paiement en attente", "Validation en attente", "Validée", "Terminée"})
     * @ORM\Column(type="string", length=55, nullable=true)
     */
    private ?string $membershipState;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(mimeTypes={"image/gif", "image/jpeg", "image/jpg", "image/png"})
     */
    private $photoName;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="participants")
     */
    private $events;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="members")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $responsibleAdult;

    /**
     * @ORM\OneToMany(targetEntity=EventSubscription::class, mappedBy="member", orphanRemoval=true)
     */
    private Collection $eventSubscriptions;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->eventSubscriptions = new ArrayCollection();
    }

    public function __toString() {
        return $this->firstName.' '.$this->lastName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getBirthdate(): ?DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

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

    public function getStreetAddress(): ?string
    {
        return $this->streetAddress;
    }

    public function setStreetAddress(string $streetAddress): self
    {
        $this->streetAddress = $streetAddress;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(?string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getResponsibleAdult(): User
    {
        return $this->responsibleAdult;
    }

    public function setResponsibleAdult(User $responsibleAdult): self
    {
        $this->responsibleAdult = $responsibleAdult;

        return $this;
    }


    public function getEmergencyPhone(): ?string
    {
        return $this->emergencyPhone;
    }

    public function setEmergencyPhone(string $emergencyPhone): self
    {
        $this->emergencyPhone = $emergencyPhone;

        return $this;
    }

    public function getMembershipState(): ?string
    {
        return $this->membershipState;
    }

    public function setMembershipState(string $membershipState): self
    {
        $this->membershipState = $membershipState;

        return $this;
    }

    public function getPhotoName(): ?string
    {
        return $this->photoName;
    }

    public function setPhotoName(?string $photoName): self
    {
        $this->photoName = $photoName;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->addParticipant($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            $event->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, EventSubscription>
     */
    public function getEventSubscriptions(): Collection
    {
        return $this->eventSubscriptions;
    }

    public function addEventSubscription(EventSubscription $eventSubscription): self
    {
        if (!$this->eventSubscriptions->contains($eventSubscription)) {
            $this->eventSubscriptions[] = $eventSubscription;
            $eventSubscription->setMember($this);
        }

        return $this;
    }

    public function removeEventSubscription(EventSubscription $eventSubscription): self
    {
        if ($this->eventSubscriptions->removeElement($eventSubscription)) {
            // set the owning side to null (unless already changed)
            if ($eventSubscription->getMember() === $this) {
                $eventSubscription->setMember(null);
            }
        }

        return $this;
    }

    public function getAdhesion(){
        foreach ($this->eventSubscriptions as $eventSubscription) {
            if (!is_null($eventSubscription->getEvent()->getSeason())) {
                return $eventSubscription->getEvent();
            }
        }

        return "";
    }
}
