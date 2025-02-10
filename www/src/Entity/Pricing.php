<?php

namespace App\Entity;

use App\Repository\PricingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PricingRepository::class)]
class Pricing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2)]
    private ?string $price = null;

    #[ORM\ManyToOne(inversedBy: 'pricings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Accommodation $accommodation = null;

    #[ORM\ManyToOne(inversedBy: 'pricings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Season $season = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

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

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): static
    {
        $this->season = $season;

        return $this;
    }
}
