<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
class Participant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "L'e-mail ne peut pas être vide.")]
    #[Assert\Email(message: "L\'adresse email n\'est pas un email valide.")]
    private ?string $email = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank(message: "La longitude ne peut pas être vide.")]
    private ?string $location_longitude = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank(message: "La latitude ne peut pas être vide.")]
    private ?string $location_latitude = null;

    #[ORM\ManyToOne(inversedBy: 'participants')]
    private ?Event $event = null;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

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

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }
}
