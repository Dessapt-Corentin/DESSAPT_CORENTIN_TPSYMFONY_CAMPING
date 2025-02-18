<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RentalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RentalRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['rental:read']],
    denormalizationContext: ['groups' => ['rental:write']]
)]
class Rental
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['rental:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rentals')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['rental:read'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'rentals')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['rental:read'])]
    private ?Accommodation $accommodation = null;

    #[ORM\Column]
    #[Groups(['rental:read'])]
    private ?int $adult_number = null;

    #[ORM\Column]
    #[Groups(['rental:read'])]
    private ?int $child_number = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['rental:read'])]
    private ?\DateTimeInterface $date_start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['rental:read'])]
    private ?\DateTimeInterface $date_end = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getAccommodation(): ?Accommodation
    {
        return $this->accommodation;
    }

    public function setAccommodation(?Accommodation $accommodation): static
    {
        $this->accommodation = $accommodation;

        return $this;
    }

    public function getAdultNumber(): ?int
    {
        return $this->adult_number;
    }

    public function setAdultNumber(int $adult_number): static
    {
        $this->adult_number = $adult_number;

        return $this;
    }

    public function getChildNumber(): ?int
    {
        return $this->child_number;
    }

    public function setChildNumber(int $child_number): static
    {
        $this->child_number = $child_number;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTimeInterface $date_start): static
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): static
    {
        $this->date_end = $date_end;

        return $this;
    }
}
