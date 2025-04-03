<?php

namespace App\Entity;

use App\Repository\FiltreValeurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FiltreValeurRepository::class)]
class FiltreValeur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $valeur = null;

    #[ORM\ManyToOne(inversedBy: 'filtreValeurs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Filtre $filtre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setValeur(string $valeur): static
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getFiltre(): ?Filtre
    {
        return $this->filtre;
    }

    public function setFiltre(?Filtre $filtre): static
    {
        $this->filtre = $filtre;

        return $this;
    }

    public function __toString(): string
    {
        return $this->valeur ?? '';
    }
}
