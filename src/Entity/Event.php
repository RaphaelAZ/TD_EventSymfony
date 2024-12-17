<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\NotBlank(message: "La date ne peut pas être vide.")]
    #[Assert\GreaterThanOrEqual("today", message: "La date doit être supérieure ou égale à aujourd'hui.")]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank(message: "La longitude ne peut pas être vide.")]
    #[Assert\Range(min: -180, max: 180, notInRangeMessage: "La longitude doit être comprise entre {{ min }}° et {{ max }}°")]
    private ?string $location_longitude = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank(message: "La latitude ne peut pas être vide.")]
    #[Assert\Range(min: -90, max: 90, notInRangeMessage: "La latitude doit être comprise entre {{ min }}° et {{ max }}°")]
    private ?string $location_latitude = null;

    /**
     * @var Collection<int, Participant>
     */
    #[ORM\OneToMany(targetEntity: Participant::class, mappedBy: 'event')]
    private Collection $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getLocationLongitude(): ?string
    {
        return $this->location_longitude;
    }

    public function setLocationLongitude(string $location_longitude): static
    {
        $this->location_longitude = $location_longitude;

        return $this;
    }

    public function getLocationLatitude(): ?string
    {
        return $this->location_latitude;
    }

    public function setLocationLatitude(string $location_latitude): static
    {
        $this->location_latitude = $location_latitude;

        return $this;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->setEvent($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): static
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getEvent() === $this) {
                $participant->setEvent(null);
            }
        }

        return $this;
    }
}
